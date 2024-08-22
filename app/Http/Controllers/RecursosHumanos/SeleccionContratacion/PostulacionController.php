<?php

namespace App\Http\Controllers\RecursosHumanos\SeleccionContratacion;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\SeleccionContratacion\PostulacionRequest;
use App\Http\Resources\RecursosHumanos\SeleccionContratacion\PostulacionResource;
use App\Models\RecursosHumanos\SeleccionContratacion\Postulacion;
use App\Models\RecursosHumanos\SeleccionContratacion\Postulante;
use App\Models\RecursosHumanos\SeleccionContratacion\UserExternal;
use App\Models\RecursosHumanos\SeleccionContratacion\Vacante;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\App\ArchivoService;
use Src\App\RecursosHumanos\SeleccionContratacion\PostulacionService;
use Src\Config\RutasStorage;
use Src\Shared\ObtenerInstanciaUsuario;
use Src\Shared\Utils;
use Throwable;

class PostulacionController extends Controller
{
    private string $entidad = 'Postulación';
    private ArchivoService $archivoService;
    private PostulacionService $service;

    public function __construct()
    {
        $this->service = new PostulacionService();
        $this->archivoService = new ArchivoService();
        // Asegura que el usuario esté autenticado en todas las acciones
        $this->middleware('check.user.logged.in');

        // $this->middleware('can:puede.crear.rrhh_vacantes')->only('store');
        $this->middleware('can:puede.editar.rrhh_vacantes')->only('update');
        $this->middleware('can:puede.eliminar.rrhh_vacantes')->only('destroy');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = Postulacion::ignoreRequest(['campos'])->filter()->orderBy('id', 'desc')->get();
        $results = PostulacionResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostulacionRequest $request)
    {
        $datos = $request->validated();

        try {
            DB::beginTransaction();
            if (auth()->user() instanceof User) {
                $postulacion = auth()->user()->postulacion()->create($datos);
                // Log::channel('testing')->info('Log', ['store::postulacion->user', $postulacion,]);
            } elseif (auth()->user() instanceof UserExternal) {
                $postulacion = auth()->user()->postulacion()->create($datos);
                // Log::channel('testing')->info('Log', ['store::postulacion->userExternal', $postulacion]);
                //Postulante hace referencia a la tabla postulante, que es equivalente a la tabla empleados, solo que en este caso es para usuarios externos
                $postulante = Postulante::find($postulacion->user_id);
                if (is_null($postulante->correo_personal)) $postulante->correo_personal = $datos['correo_personal'];
                if (is_null($postulante->direccion)) $postulante->direccion = $datos['direccion'];
                if (is_null($postulante->fecha_nacimiento)) $postulante->fecha_nacimiento = $datos['fecha_nacimiento'];
                if (is_null($postulante->genero)) $postulante->genero = $datos['genero'];
                if (is_null($postulante->identidad_genero_id)) $postulante->identidad_genero_id = $datos['identidad_genero_id'];
                if (is_null($postulante->pais_id)) $postulante->pais_id = $datos['pais_id'];
                $postulante->save();
            }
            if ($postulacion->vacante_id) {
                $vacante = Vacante::find($postulacion->vacante_id);
                if ($vacante) {
                    $vacante->update(['numero_postulantes' => $vacante->numero_postulantes + 1]);
                    $vacante->save();
                }
            }
            // throw new Exception('Excepcion controlada');
            $modelo = new PostulacionResource($postulacion);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
        } catch (Throwable $e) {
            DB::rollback();
            throw Utils::obtenerMensajeErrorLanzable($e);
        }

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Postulacion $postulacion)
    {
        $leido_old = false;
        if (auth()->user()->hasRole(User::ROL_RECURSOS_HUMANOS) && request()->boolean('leido')) {
            $leido_old = $postulacion->leido_rrhh;
            if (request()->boolean('leido')) {
                if (!$leido_old) {
                    // Ya que leído es falso, se actualiza a true y se notifica por correo electronico al postulante que RRHH ha visto su CV
                    $postulacion->estado = Postulacion::REVISION_CV;
                    $postulacion->leido_rrhh = true;
                    $postulacion->save();
                    // Notificar al postulante
                    $this->service->notificarPostulacionLeida($postulacion->refresh());
                }
            }
        }
        $modelo = new PostulacionResource($postulacion);



        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostulacionRequest $request, Postulacion $postulacion)
    {
        try {
            DB::beginTransaction();
            throw new Exception('Metodo no configurado aún');
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw Utils::obtenerMensajeErrorLanzable($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            throw new Exception('Metodo no configurado aún. Comunícate con Dept. Informático.');
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw Utils::obtenerMensajeErrorLanzable($th);
        }
    }

    /**
     * Listar todos los CV del usuario logueado
     */
    public function curriculumUsuario()
    {
        // se trabaja con la sesión del usuario logueado
        try {
            [$user_id, $user_type] = ObtenerInstanciaUsuario::tipoUsuario();

            $user = match ($user_type) {
                User::class => User::find($user_id),
                UserExternal::class => UserExternal::find($user_id),
                default => null,
            };
            $results = $this->archivoService->listarArchivos($user);
            return response()->json(compact('results'));
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
            return response()->json(compact('mensaje'), 500);
        }
    }

    /**
     * Listar archivos
     */
    public function indexFiles(Postulacion $postulacion)
    {
        // Log::channel('testing')->info('Log', ['indexFiles de postulacion', $postulacion, request()->all()]);
        try {
            $user_id = $postulacion->user_id;
            $user_type = $postulacion->user_type;

            $user = match ($user_type) {
                User::class => User::find($user_id),
                UserExternal::class => UserExternal::find($user_id),
                default => null,
            };
            $results = $this->archivoService->listarArchivos($user);

            $filtrados = $results->filter(function ($archivo) use ($postulacion) {
                return $archivo->ruta == $postulacion->ruta_cv;
            });

            $results = $filtrados->values()->toArray();

            return response()->json(compact('results'));
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
            return response()->json(compact('mensaje'), 500);
        }
    }

    /**
     * Guardar archivos
     */
    public function storeFiles(Request $request, Postulacion $postulacion)
    {
        $ruta = '';
        // Log::channel('testing')->info('Log', ['storeFiles de postulacion', $postulacion, request()->all()]);
        try {
            [$user_id, $user_type] = ObtenerInstanciaUsuario::tipoUsuario();
            if (!is_null($user_id)) {
                $user = match ($user_type) {
                    User::class => User::find($user_id),
                    UserExternal::class => UserExternal::find($user_id),
                    default => null,
                };
                // Log::channel('testing')->info('Log', ['user es', $user]);
                // Hay que configurar para que se guarden los CV´s de los postulantes con su respectivo numero de cedula
                // para luego poder buscarlos y versionarlos así como LinkedIn
                $ruta = match ($user_type) {
                    User::class =>  RutasStorage::CURRICULUM->value . $user->empleado->identificacion,
                    UserExternal::class =>  RutasStorage::CURRICULUM->value . $user->persona->numero_documento_identificacion,
                    default => null,
                };

                $modelo = $this->archivoService->guardarArchivo($user, $request->file, $ruta, $request->tipo);
                // Log::channel('testing')->info('Log', ['modelo obtenido es', $modelo]);

                // se actualiza la postulación para anexar el archivo del CV
                $postulacion->ruta_cv = $modelo->ruta;
                $postulacion->save();

                $mensaje = 'Archivo subido correctamente';
            } else {
                throw new Exception('Usuario no logueado');
            }
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Throwable $th) {
            $mensaje = $th->getMessage() . '. ' . $th->getLine();
            return response()->json(compact('mensaje'), 500);
        }
    }
}
