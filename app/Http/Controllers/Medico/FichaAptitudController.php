<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\FichaAptitudRequest;
use App\Http\Resources\Medico\FichaAptitudResource;
use App\Models\ConfiguracionGeneral;
use App\Models\Empleado;
use App\Models\Medico\FichaAptitud;
use App\Models\Medico\ProfesionalSalud;
use App\Models\Medico\TipoAptitudMedicaLaboral;
use App\Models\Medico\TipoEvaluacionMedicaRetiro;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;
use Throwable;

class FichaAptitudController extends Controller
{
    private string $entidad = 'Examen Ficha de aptitud';

    public function __construct()
    {
        $this->middleware('can:puede.ver.fichas_aptitudes')->only('index', 'show');
        $this->middleware('can:puede.crear.fichas_aptitudes')->only('store');
        $this->middleware('can:puede.editar.fichas_aptitudes')->only('update');
        $this->middleware('can:puede.eliminar.fichas_aptitudes')->only('destroy');
    }

    public function index()
    {
        $results = FichaAptitud::ignoreRequest(['campos'])->filter()->get();
        $results = FichaAptitudResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * @throws Throwable
     */
    public function store(FichaAptitudRequest $request)
    {
        try {
            DB::beginTransaction();

            $datos = $request->validated();
            $datos['firmado_profesional_salud'] = true;
            $ficha_aptitud = FichaAptitud::create($datos);

            // opcionesRespuestasTipoEvaluacionMedicaRetiro
            foreach ($datos['opciones_respuestas_tipo_evaluacion_medica_retiro'] as $opcion) {
                $opcion['tipo_evaluacion_medica_retiro_id'] = $opcion['tipo_evaluacion_medica_retiro'];
                $ficha_aptitud->opcionesRespuestasTipoEvaluacionMedicaRetiro()->create($opcion);
            }

            $modelo = new FichaAptitudResource($ficha_aptitud->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de ficha de aptitud' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(FichaAptitud $ficha_aptitud)
    {
        $modelo = new FichaAptitudResource($ficha_aptitud);
        return response()->json(compact('modelo'));
    }


    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function update(FichaAptitudRequest $request, FichaAptitud $ficha_aptitud)
    {
        Log::channel('testing')->info('Dentro de update... ');
        try {
            Log::channel('testing')->info('Dentro de update try... ');
            // DB::beginTransaction();

            // if ($request->isMethod('patch')) {
            $keys = $request->keys();
            Log::channel('testing')->info('Dentro de update... ', [$keys]);
            unset($keys['id']);
            // Log::channel('testing')->info('Keys ' . $request->keys());
            // Log::channel('testing')->info('Keys only ' . $request->only($request->keys()));
            $ficha_aptitud->update($request->only($request->keys()));
            // }

            // $datos = $request->validated();
            // $ficha_aptitud->update($datos);
            $modelo = new FichaAptitudResource($ficha_aptitud->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

            // DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al actualizar el registro' => [$e->getMessage()],
            ]);
        }
    }

    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function destroy(FichaAptitud $ficha_aptitud)
    {
        try {
            DB::beginTransaction();
            $ficha_aptitud->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al eliminar el registro' => [$e->getMessage()],
            ]);
        }
    }

    public function imprimirPDF(FichaAptitud $ficha_aptitud)
    {
        $configuracion = ConfiguracionGeneral::first();
        $resource = new FichaAptitudResource($ficha_aptitud);
        $empleado = Empleado::find($ficha_aptitud->registroEmpleadoExamen->empleado_id);
        $profesionalSalud = ProfesionalSalud::find($ficha_aptitud->profesional_salud_id);

        $respuestasTiposEvaluacionesMedicasRetiros = [
            ['SI', 'NO'],
            ['PRESUNTIVA', 'DEFINITIVA', 'NO APLICA'],
            ['SI', 'NO', 'NO APLICA'],
        ];

        // revisar que no se descarga
        // {
        //    "message": "Undefined array key 3",
        //    "exception": "ErrorException",
        //    "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/app/Http/Controllers/Medico/FichaAptitudController.php",
        //    "line": 145,
        //    "trace": [
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Foundation/Bootstrap/HandleExceptions.php",
        //            "line": 272,
        //            "function": "handleError",
        //            "class": "Illuminate\\Foundation\\Bootstrap\\HandleExceptions",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/app/Http/Controllers/Medico/FichaAptitudController.php",
        //            "line": 145,
        //            "function": "Illuminate\\Foundation\\Bootstrap\\{closure}",
        //            "class": "Illuminate\\Foundation\\Bootstrap\\HandleExceptions",
        //            "type": "->"
        //        },
        //        {
        //            "function": "App\\Http\\Controllers\\Medico\\{closure}",
        //            "class": "App\\Http\\Controllers\\Medico\\FichaAptitudController",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Collections/Arr.php",
        //            "line": 560,
        //            "function": "array_map"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Collections/Collection.php",
        //            "line": 768,
        //            "function": "map",
        //            "class": "Illuminate\\Support\\Arr",
        //            "type": "::"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Collection.php",
        //            "line": 341,
        //            "function": "map",
        //            "class": "Illuminate\\Support\\Collection",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/app/Http/Controllers/Medico/FichaAptitudController.php",
        //            "line": 141,
        //            "function": "map",
        //            "class": "Illuminate\\Database\\Eloquent\\Collection",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Routing/Controller.php",
        //            "line": 54,
        //            "function": "imprimirPDF",
        //            "class": "App\\Http\\Controllers\\Medico\\FichaAptitudController",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Routing/ControllerDispatcher.php",
        //            "line": 43,
        //            "function": "callAction",
        //            "class": "Illuminate\\Routing\\Controller",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Routing/Route.php",
        //            "line": 259,
        //            "function": "dispatch",
        //            "class": "Illuminate\\Routing\\ControllerDispatcher",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Routing/Route.php",
        //            "line": 205,
        //            "function": "runController",
        //            "class": "Illuminate\\Routing\\Route",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Routing/Router.php",
        //            "line": 798,
        //            "function": "run",
        //            "class": "Illuminate\\Routing\\Route",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
        //            "line": 141,
        //            "function": "Illuminate\\Routing\\{closure}",
        //            "class": "Illuminate\\Routing\\Router",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/app/Http/Middleware/CheckUserDesactivado.php",
        //            "line": 28,
        //            "function": "Illuminate\\Pipeline\\{closure}",
        //            "class": "Illuminate\\Pipeline\\Pipeline",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
        //            "line": 180,
        //            "function": "handle",
        //            "class": "App\\Http\\Middleware\\CheckUserDesactivado",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Routing/Middleware/SubstituteBindings.php",
        //            "line": 50,
        //            "function": "Illuminate\\Pipeline\\{closure}",
        //            "class": "Illuminate\\Pipeline\\Pipeline",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
        //            "line": 180,
        //            "function": "handle",
        //            "class": "Illuminate\\Routing\\Middleware\\SubstituteBindings",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Routing/Middleware/ThrottleRequests.php",
        //            "line": 126,
        //            "function": "Illuminate\\Pipeline\\{closure}",
        //            "class": "Illuminate\\Pipeline\\Pipeline",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Routing/Middleware/ThrottleRequests.php",
        //            "line": 92,
        //            "function": "handleRequest",
        //            "class": "Illuminate\\Routing\\Middleware\\ThrottleRequests",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Routing/Middleware/ThrottleRequests.php",
        //            "line": 54,
        //            "function": "handleRequestUsingNamedLimiter",
        //            "class": "Illuminate\\Routing\\Middleware\\ThrottleRequests",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
        //            "line": 180,
        //            "function": "handle",
        //            "class": "Illuminate\\Routing\\Middleware\\ThrottleRequests",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Auth/Middleware/Authenticate.php",
        //            "line": 44,
        //            "function": "Illuminate\\Pipeline\\{closure}",
        //            "class": "Illuminate\\Pipeline\\Pipeline",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
        //            "line": 180,
        //            "function": "handle",
        //            "class": "Illuminate\\Auth\\Middleware\\Authenticate",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/sanctum/src/Http/Middleware/EnsureFrontendRequestsAreStateful.php",
        //            "line": 25,
        //            "function": "Illuminate\\Pipeline\\{closure}",
        //            "class": "Illuminate\\Pipeline\\Pipeline",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
        //            "line": 141,
        //            "function": "Laravel\\Sanctum\\Http\\Middleware\\{closure}",
        //            "class": "Laravel\\Sanctum\\Http\\Middleware\\EnsureFrontendRequestsAreStateful",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
        //            "line": 116,
        //            "function": "Illuminate\\Pipeline\\{closure}",
        //            "class": "Illuminate\\Pipeline\\Pipeline",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/sanctum/src/Http/Middleware/EnsureFrontendRequestsAreStateful.php",
        //            "line": 24,
        //            "function": "then",
        //            "class": "Illuminate\\Pipeline\\Pipeline",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
        //            "line": 180,
        //            "function": "handle",
        //            "class": "Laravel\\Sanctum\\Http\\Middleware\\EnsureFrontendRequestsAreStateful",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
        //            "line": 116,
        //            "function": "Illuminate\\Pipeline\\{closure}",
        //            "class": "Illuminate\\Pipeline\\Pipeline",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Routing/Router.php",
        //            "line": 797,
        //            "function": "then",
        //            "class": "Illuminate\\Pipeline\\Pipeline",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Routing/Router.php",
        //            "line": 776,
        //            "function": "runRouteWithinStack",
        //            "class": "Illuminate\\Routing\\Router",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Routing/Router.php",
        //            "line": 740,
        //            "function": "runRoute",
        //            "class": "Illuminate\\Routing\\Router",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Routing/Router.php",
        //            "line": 729,
        //            "function": "dispatchToRoute",
        //            "class": "Illuminate\\Routing\\Router",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php",
        //            "line": 190,
        //            "function": "dispatch",
        //            "class": "Illuminate\\Routing\\Router",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
        //            "line": 141,
        //            "function": "Illuminate\\Foundation\\Http\\{closure}",
        //            "class": "Illuminate\\Foundation\\Http\\Kernel",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/app/Http/Middleware/CheckAndFetchImageOrFile.php",
        //            "line": 65,
        //            "function": "Illuminate\\Pipeline\\{closure}",
        //            "class": "Illuminate\\Pipeline\\Pipeline",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
        //            "line": 180,
        //            "function": "handle",
        //            "class": "App\\Http\\Middleware\\CheckAndFetchImageOrFile",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php",
        //            "line": 21,
        //            "function": "Illuminate\\Pipeline\\{closure}",
        //            "class": "Illuminate\\Pipeline\\Pipeline",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/ConvertEmptyStringsToNull.php",
        //            "line": 31,
        //            "function": "handle",
        //            "class": "Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
        //            "line": 180,
        //            "function": "handle",
        //            "class": "Illuminate\\Foundation\\Http\\Middleware\\ConvertEmptyStringsToNull",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php",
        //            "line": 21,
        //            "function": "Illuminate\\Pipeline\\{closure}",
        //            "class": "Illuminate\\Pipeline\\Pipeline",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TrimStrings.php",
        //            "line": 40,
        //            "function": "handle",
        //            "class": "Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
        //            "line": 180,
        //            "function": "handle",
        //            "class": "Illuminate\\Foundation\\Http\\Middleware\\TrimStrings",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/ValidatePostSize.php",
        //            "line": 27,
        //            "function": "Illuminate\\Pipeline\\{closure}",
        //            "class": "Illuminate\\Pipeline\\Pipeline",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
        //            "line": 180,
        //            "function": "handle",
        //            "class": "Illuminate\\Foundation\\Http\\Middleware\\ValidatePostSize",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/PreventRequestsDuringMaintenance.php",
        //            "line": 86,
        //            "function": "Illuminate\\Pipeline\\{closure}",
        //            "class": "Illuminate\\Pipeline\\Pipeline",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
        //            "line": 180,
        //            "function": "handle",
        //            "class": "Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Http/Middleware/HandleCors.php",
        //            "line": 62,
        //            "function": "Illuminate\\Pipeline\\{closure}",
        //            "class": "Illuminate\\Pipeline\\Pipeline",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
        //            "line": 180,
        //            "function": "handle",
        //            "class": "Illuminate\\Http\\Middleware\\HandleCors",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Http/Middleware/TrustProxies.php",
        //            "line": 39,
        //            "function": "Illuminate\\Pipeline\\{closure}",
        //            "class": "Illuminate\\Pipeline\\Pipeline",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
        //            "line": 180,
        //            "function": "handle",
        //            "class": "Illuminate\\Http\\Middleware\\TrustProxies",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php",
        //            "line": 116,
        //            "function": "Illuminate\\Pipeline\\{closure}",
        //            "class": "Illuminate\\Pipeline\\Pipeline",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php",
        //            "line": 165,
        //            "function": "then",
        //            "class": "Illuminate\\Pipeline\\Pipeline",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php",
        //            "line": 134,
        //            "function": "sendRequestThroughRouter",
        //            "class": "Illuminate\\Foundation\\Http\\Kernel",
        //            "type": "->"
        //        },
        //        {
        //            "file": "/home/jpconst1/api-sistemas.jpconstrucred.com/public/index.php",
        //            "line": 51,
        //            "function": "handle",
        //            "class": "Illuminate\\Foundation\\Http\\Kernel",
        //            "type": "->"
        //        }
        //    ]
        //}
        $opcionesRespuestasTipoEvaluacionMedicaRetiro = TipoEvaluacionMedicaRetiro::all()->map(function ($tipo, $index) use ($respuestasTiposEvaluacionesMedicasRetiros, $ficha_aptitud) {
            return [
                'id' => $tipo->id,
                'nombre' => $tipo->nombre,
                'posibles_respuestas' => $respuestasTiposEvaluacionesMedicasRetiros[$index],
                'respuesta' => $ficha_aptitud->opcionesRespuestasTipoEvaluacionMedicaRetiro->first(fn ($opcion) => $opcion->tipo_evaluacion_medica_retiro_id === $tipo->id)->respuesta,
            ];
        });

        $tipos_aptitudes_medicas_laborales = TipoAptitudMedicaLaboral::all()->map(function ($tipo) use ($ficha_aptitud) {
            if ($tipo->id === $ficha_aptitud->tipo_aptitud_medica_laboral_id) $tipo->seleccionado = true;
            else $tipo->seleccionado = false;
            return $tipo;
        });

        $datos = [
            'ficha_aptitud' => $resource->resolve(),
            'configuracion' => $configuracion,
            'empleado' => $empleado,
            'opcionesRespuestasTipoEvaluacionMedicaRetiro' => $opcionesRespuestasTipoEvaluacionMedicaRetiro,
            'tipos_aptitudes_medicas_laborales' => $tipos_aptitudes_medicas_laborales,
            'profesionalSalud' => $profesionalSalud,
            'firmaProfesionalMedico' => 'data:image/png;base64,' . base64_encode(file_get_contents(substr($profesionalSalud->empleado->firma_url, 1))),
            // 'firmaPaciente' => 'data:image/png;base64,' . base64_encode(file_get_contents(substr($ficha_aptitud->registroEmpleadoExamen->empleado->firma_url, 1))),
            'tipo_proceso_examen' => $ficha_aptitud->registroEmpleadoExamen->tipo_proceso_examen,
        ];

        try {
            $pdf = Pdf::loadView('medico.pdf.ficha_aptitud', $datos);
            $pdf->setPaper('A4');
            $pdf->setOption(['isRemoteEnabled' => true]);
            $pdf->render();

            return $pdf->output();
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['ERROR', $ex->getMessage(), $ex->getLine()]);
            $mensaje = $ex->getMessage() . '. ' . $ex->getLine();
            return response()->json(compact('mensaje'));
        }
    }
}
