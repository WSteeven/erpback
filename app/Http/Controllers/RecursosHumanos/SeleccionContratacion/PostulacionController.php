<?php

namespace App\Http\Controllers\RecursosHumanos\SeleccionContratacion;

use App\Events\RecursosHumanos\SeleccionContratacion\NotificarRecursosHumanosNuevaPostulacion;
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
use Src\App\PolymorphicGenericService;
use Src\App\RecursosHumanos\SeleccionContratacion\EvaluacionPersonalidadService;
use Src\App\RecursosHumanos\SeleccionContratacion\GeneradorExcelTestPersonalidad;
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
    private PolymorphicGenericService $polymorficGenericService;
    private PostulacionService $service;

    public function __construct()
    {
        $this->polymorficSeleccionContratacionService = new PolymorphicSeleccionContratacionModelsService();
        $this->polymorficGenericService = new PolymorphicGenericService();
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
        [, $user_type] = ObtenerInstanciaUsuario::tipoUsuario();
        if (request('user_id'))
            $results = Postulacion::ignoreRequest(['campos'])->where('user_type', $user_type)->filter()->orderBy('id', 'desc')->get();
        else
            $results = Postulacion::ignoreRequest(['campos'])->filter()->orderBy('id', 'desc')->get();
        $results = PostulacionResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PostulacionRequest $request
     * @return JsonResponse
     * @throws ValidationException|Throwable
     */
    public function store(PostulacionRequest $request)
    {
        $datos = $request->validated();
        $postulacion = null;
        Log::channel('testing')->info('Log', ['store::postulacion->user', $request->all(), $datos, auth()->user()->getAuthIdentifier()]);
        try {
            DB::beginTransaction();
            if (auth()->user() instanceof User) {
                $postulacion = auth()->user()->postulaciones()->create($datos);
                $this->polymorficSeleccionContratacionService->actualizarReferenciasPersonales(User::find(auth()->user()->getAuthIdentifier()), $datos['referencias']);
                $this->polymorficGenericService->actualizarDiscapacidades(User::find(auth()->user()->getAuthIdentifier()), $datos['discapacidades']);
            } elseif (auth()->user() instanceof UserExternal) {
                $postulacion = auth()->user()->postulaciones()->create($datos);
                $this->polymorficSeleccionContratacionService->actualizarReferenciasPersonales(UserExternal::find(auth()->user()->getAuthIdentifier()), $datos['referencias']);
                $this->polymorficGenericService->actualizarDiscapacidades(UserExternal::find(auth()->user()->getAuthIdentifier()), $datos['discapacidades']);
//                Log::channel('testing')->info('Log', ['store::postulacion->userExternal', $postulacion]);
                //Postulante hace referencia a la tabla postulante, que es equivalente a la tabla empleados, solo que en este caso es para usuarios externos
                $postulante = Postulante::where('usuario_external_id', $postulacion->user_id)->first();
                $postulante->correo_personal = $datos['correo_personal'];
                $postulante->direccion = $datos['direccion'];
                $postulante->fecha_nacimiento = $datos['fecha_nacimiento'];
                $postulante->genero = $datos['genero'];
                $postulante->identidad_genero_id = $datos['identidad_genero_id'];
                if (is_null($postulante->pais_id)) $postulante->pais_id = $datos['pais_id'];
                $postulante->save();
            }
            event(new NotificarRecursosHumanosNuevaPostulacion($postulacion->id));
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
     * Listar todos los CV del usuario logueado
     */
    public function curriculumUsuario()
    {
        // se trabaja con la sesión del usuario logueado
        try {
            [, , $user] = ObtenerInstanciaUsuario::tipoUsuario();

//            $user = match ($user_type) {
//                User::class => User::find($user_id),
//                UserExternal::class => UserExternal::find($user_id),
//                default => null,
//            };
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
            [, , $user] = ObtenerInstanciaUsuario::tipoUsuario();

//            $user = match ($user_type) {
//                User::class => User::find($user_id),
//                UserExternal::class => UserExternal::find($user_id),
//                default => null,
//            };
            $results = $user->referencias()->get();
            return response()->json(compact('results'));
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
            return response()->json(compact('mensaje'), 500);
        }

    }

    /**
     * @param Postulacion $postulacion
     * @return JsonResponse
     * @throws Throwable
     */
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
        } catch (Throwable $ex) {
            DB::rollback();
            $mensaje = $ex->getMessage();
            return response()->json(compact('mensaje'), 500);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * @param Postulacion $postulacion
     * @return JsonResponse
     * @throws Throwable
     */
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
        } catch (Throwable $ex) {
            DB::rollback();
            $mensaje = $ex->getMessage();
            return response()->json(compact('mensaje'), 500);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * @throws ValidationException|Throwable
     */
    public function seleccionar(Postulacion $postulacion)
    {
        try {
            DB::beginTransaction();
            $postulacion->estado = Postulacion::SELECCIONADO;
            $postulacion->save();
            $this->service->notificarPostulanteSeleccionado($postulacion);
            $this->service->notificarPostulanteSeleccionadoMedico($postulacion->id);
            $modelo = new PostulacionResource($postulacion);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
        } catch (Throwable $ex) {
            DB::rollback();
            Log::channel('testing')->info('Log', ['error en seleccionar', $ex->getLine(), $ex->getMessage()]);
            throw  Utils::obtenerMensajeErrorLanzable($ex);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * @param Postulacion $postulacion
     * @return JsonResponse
     * @throws Throwable
     */
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
        } catch (Throwable $ex) {
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
            [$user_id, $user_type, $user] = ObtenerInstanciaUsuario::tipoUsuario();
            if (!is_null($user_id)) {
//                $user = match ($user_type) {
//                    User::class => User::find($user_id),
//                    UserExternal::class => UserExternal::find($user_id),
//                    default => null,
//                };
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

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws Exception
     */
    public function descargarTestPersonalidadCompletado(Postulacion $postulacion)
    {
        // Se verifica si ya hay una evaluacion completada para esta postulación y en ese caso retorna la evaluación
        if (EvaluacionPersonalidadService::verificarExisteEvaluacionPostulacion($postulacion->id, true)) {
            $generadorExcel = new GeneradorExcelTestPersonalidad();
            $tempFile = $generadorExcel->generar($postulacion);
            return response()->download($tempFile, 'evaluacion_personalidad.xlsx')->deleteFileAfterSend();
        }
        throw new Exception("No se encontró una evaluación de personalidad completada");
    }

    public function habilitarTestPersonalidad(Postulacion $postulacion)
    {
        $this->service->generarTokenSiNoExiste($postulacion);
        $this->service->crearEvaluacionSiNoexiste($postulacion);

        $q = match (get_class($postulacion->user)) {
            UserExternal::class => 'external',
            default => '', // no se devuelve nada porque el front solo evalua si es explicitamente 'external'
        };
        $link = env('SPA_URL') . "/test-personalidad/$postulacion->token_test?q=$q";
        $this->service->enviarLinkSiNoFueEnviado($postulacion, $link);

        $modelo = new PostulacionResource($postulacion);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('modelo', 'mensaje', 'link'));
    }

    /**
     * @throws Exception
     */
    public function validarTokenTestPersonalidad(string $token)
    {
        // Si no hay test contestado, por defecto es false
        $contestado = false;

        try {
            [$user_id, $user_type] = ObtenerInstanciaUsuario::tipoUsuario();
            $postulacion = Postulacion::where('user_id', $user_id)->where('user_type', $user_type)->where('token_test', $token)->first();
            if (!$postulacion) throw new Exception('No se encontró un postulación válida para ese token');
            $mensaje = 'Token validado correctamente, puedes contestar el test';

            $existeEvaluacionCompletada = EvaluacionPersonalidadService::verificarExisteEvaluacionPostulacion($postulacion->id, true);
            if ($existeEvaluacionCompletada) {
                $contestado = true;
                $mensaje = 'Ya has contestado este test previamente. ¡Proceso finalizado!';
            }

        } catch (Exception $ex) {
            throw Utils::obtenerMensajeErrorLanzable($ex);
        }
        return response()->json(compact('mensaje', 'contestado'));
    }

    public function chequearTieneEvaluacionPersonalidad(Postulacion $postulacion)
    {
        $completada = EvaluacionPersonalidadService::verificarExisteEvaluacionPostulacion($postulacion->id, true);

        return response()->json(compact('completada'));
    }
}
