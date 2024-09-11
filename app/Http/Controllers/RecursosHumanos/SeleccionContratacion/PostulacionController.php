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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\ArchivoService;
use Src\App\RecursosHumanos\SeleccionContratacion\PolymorphicSeleccionContratacionModelsService;
use Src\App\RecursosHumanos\SeleccionContratacion\PostulacionService;
use Src\Config\RutasStorage;
use Src\Shared\ObtenerInstanciaUsuario;
use Src\Shared\Utils;
use Throwable;

class PostulacionController extends Controller
{
    private string $entidad = 'Postulación';
    private ArchivoService $archivoService;
    private PolymorphicSeleccionContratacionModelsService $polymorficSeleccionContratacionService;
    private PostulacionService $service;

    public function __construct()
    {
        $this->polymorficSeleccionContratacionService = new PolymorphicSeleccionContratacionModelsService();
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
     * @return JsonResponse
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
     * @param PostulacionRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(PostulacionRequest $request)
    {
        $datos = $request->validated();
        $postulacion = null;
        Log::channel('testing')->info('Log', ['store::postulacion->user', $request->all(), $datos, auth()->user()->getAuthIdentifier()]);
        try {
//            throw new Exception("Error controlado, quiero ver que recibo del front");
            DB::beginTransaction();
            if (auth()->user() instanceof User) {
                $postulacion = auth()->user()->postulaciones()->create($datos);
                $this->polymorficSeleccionContratacionService->actualizarReferenciasPersonales(User::find(auth()->user()->getAuthIdentifier()), $datos['referencias']);
            } elseif (auth()->user() instanceof UserExternal) {
                $postulacion = auth()->user()->postulaciones()->create($datos);
                $this->polymorficSeleccionContratacionService->actualizarReferenciasPersonales(UserExternal::find(auth()->user()->getAuthIdentifier()), $datos['referencias']);
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
     * @param Postulacion $postulacion
     * @return JsonResponse
     * @throws Throwable
     */
    public function show(Postulacion $postulacion)
    {
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
     * @param PostulacionRequest $request
     * @param Postulacion $postulacion
     * @return void
     * @throws ValidationException
     */
//    public function update(PostulacionRequest $request, Postulacion $postulacion)
//    {
//        try {
//            throw new Exception('Metodo no configurado aún');
//        } catch (Throwable $th) {
//            throw Utils::obtenerMensajeErrorLanzable($th);
//        }
//    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return void
     * @throws ValidationException
     */
//    public function destroy($id)
//    {
//        try {
//            throw new Exception('Metodo no configurado aún. Comunícate con Dept. Informático.');
//        } catch (Throwable $th) {
//            throw Utils::obtenerMensajeErrorLanzable($th);
//        }
//    }

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
//                default => null,
            };
            $results = $this->archivoService->listarArchivos($user);
            return response()->json(compact('results'));
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
            return response()->json(compact('mensaje'), 500);
        }
    }

    /**
     * Listar todas las referencias personales del usuario
     */
    public function referenciasUsuario()
    {
        try {
            [$user_id, $user_type] = ObtenerInstanciaUsuario::tipoUsuario();

            $user = match ($user_type) {
                User::class => User::find($user_id),
                UserExternal::class => UserExternal::find($user_id),
//                default => null,
            };
            $results = $user->referencias()->get();
            return response()->json(compact('results'));
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
            return response()->json(compact('mensaje'), 500);
        }

    }

    public function calificar(Postulacion $postulacion)
    {
        $modelo = null;
        try {
            DB::beginTransaction();
            $postulacion->calificacion = request()->calificacion;
            if ($postulacion->calificacion === Postulacion::NO_CONSIDERAR) {
                // notificar al usuario que no es apto para el puesto
                $postulacion->estado = Postulacion::DESCARTADO;
                $this->service->notificarPostulacionDescartada($postulacion, true);
            } else {
                $postulacion->estado = Postulacion::PRESELECCIONADO;
            }
            $postulacion->save();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
        } catch (Throwable|Exception $ex) {
            DB::rollback();
            $mensaje = $ex->getMessage();
            return response()->json(compact('mensaje'), 500);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function descartar(Postulacion $postulacion)
    {
        $estado_old = $postulacion->estado;
        try {
            DB::beginTransaction();
            if ($estado_old === Postulacion::ENTREVISTA || $estado_old === Postulacion::SELECCIONADO || $estado_old === Postulacion::EXAMENES_MEDICOS) {
                $this->service->notificarPostulacionDescartada($postulacion, false);
            } else {
                $this->service->notificarPostulacionDescartada($postulacion, true);
            }
            $postulacion->estado = Postulacion::DESCARTADO;
            $postulacion->save();
            $modelo = new PostulacionResource($postulacion);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
        } catch (Throwable|Exception $ex) {
            DB::rollback();
            $mensaje = $ex->getMessage();
            return response()->json(compact('mensaje'), 500);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * @throws ValidationException
     */
    public function seleccionar(Postulacion $postulacion)
    {
        Log::channel('testing')->info('Log', ['seleccionar', $postulacion, request()->all()]);
        try {
            DB::beginTransaction();
            $postulacion->estado = Postulacion::SELECCIONADO;
            $postulacion->save();
            $this->service->notificarPostulanteSeleccionado($postulacion);
            $this->service->notificarPostulanteSeleccionadoMedico($postulacion->id);
            $modelo = new PostulacionResource($postulacion);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
        } catch (Throwable|Exception $ex) {
            DB::rollback();
            Log::channel('testing')->info('Log', ['error en seleccionar', $ex->getLine(), $ex->getMessage()]);
            throw  Utils::obtenerMensajeErrorLanzable($ex);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function darAlta(Postulacion $postulacion)
    {
        try {
            DB::beginTransaction();
            $postulacion->dado_alta = true;
            $postulacion->save();
            $es_empleado = match ($postulacion->user_type) {
                User::class => true,
                UserExternal::class => false,
            };
            $modelo = new PostulacionResource($postulacion);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
        } catch (Throwable|Exception $ex) {
            DB::rollback();
            $mensaje = $ex->getMessage();
            Log::channel('testing')->info('Log', ['error en darAlta', $ex->getLine(), $ex->getMessage()]);
            return response()->json(compact('mensaje'), 500);
        }
        return response()->json(compact('mensaje', 'modelo', 'es_empleado'));
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
//                default => null,
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
        // Log::channel('testing')->info('Log', ['storeFiles de postulacion', $postulacion, request()->all()]);
        try {
            [$user_id, $user_type] = ObtenerInstanciaUsuario::tipoUsuario();
            if (!is_null($user_id)) {
                $user = match ($user_type) {
                    User::class => User::find($user_id),
                    UserExternal::class => UserExternal::find($user_id),
//                    default => null,
                };
                // Log::channel('testing')->info('Log', ['user es', $user]);
                // Hay que configurar para que se guarden los CV´s de los postulantes con su respectivo numero de cedula
                // para luego poder buscarlos y versionarlos así como LinkedIn
                $ruta = match ($user_type) {
                    User::class => RutasStorage::CURRICULUM->value . $user->empleado->identificacion,
                    UserExternal::class => RutasStorage::CURRICULUM->value . $user->persona->numero_documento_identificacion,
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
