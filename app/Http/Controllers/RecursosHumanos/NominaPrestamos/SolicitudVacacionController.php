<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Events\RecursosHumanos\SolicitudVacacionEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\NominaPrestamos\SolicitudVacacionRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\SolicitudVacacionResource;
use App\Models\Autorizacion;
use App\Models\ConfiguracionGeneral;
use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\Periodo;
use App\Models\RecursosHumanos\NominaPrestamos\SolicitudVacacion;
use App\Models\RecursosHumanos\NominaPrestamos\Vacacion;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\RecursosHumanos\NominaPrestamos\VacacionService;
use Src\Shared\Utils;
use Throwable;

class SolicitudVacacionController extends Controller
{
    private string $entidad = 'Solicitud de vacación';
    private VacacionService $vacacionService;

    public function __construct()
    {
        $this->vacacionService = new VacacionService();
        $this->middleware('can:puede.ver.solicitudes_vacaciones')->only('index', 'show');
        $this->middleware('can:puede.crear.solicitudes_vacaciones')->only('store');
        $this->middleware('can:puede.editar.solicitudes_vacaciones')->only('update');
        $this->middleware('can:puede.eliminar.solicitudes_vacaciones')->only('destroy');
    }

    /**
     * La función de índice recupera datos de vacaciones en función del rol del usuario y los devuelve
     * como una respuesta JSON.
     *
     * @return JsonResponse respuesta JSON que contiene la variable 'resultados'.
     */
    public function index()
    {
        if (auth()->user()->hasRole('RECURSOS HUMANOS')) {
            $results = SolicitudVacacion::ignoreRequest(['campos'])->filter()->get();
        } else
            $results = SolicitudVacacion::where('empleado_id', Auth::user()->empleado->id)
                ->orWhere('autorizador_id', Auth::user()->empleado->id)
                ->filter()->get();
        $results = SolicitudVacacionResource::collection($results);

        return response()->json(compact('results'));
    }

    /**
     * La función almacena datos de vacaciones y realiza comprobaciones de validación.
     *
     * @param SolicitudVacacionRequest $request El parámetro `` es una instancia de la clase
     * `VacacionRequest`. Se utiliza para validar y recuperar los datos enviados en la solicitud HTTP.
     *
     * @return JsonResponse respuesta JSON que contiene las variables 'mensaje' y 'modelo'.
     * @throws Throwable
     * @throws ValidationException
     */
    public function store(SolicitudVacacionRequest $request)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();

//            throw new Exception("Error controlado");
            $solicitud = SolicitudVacacion::create($datos);
            event(new SolicitudVacacionEvent($solicitud));
            $modelo = new SolicitudVacacionResource($solicitud);

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->error('Log', ['Ha ocurrido un error al insertar el registro:', $e->getMessage(), $e->getLine()]);
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function show(SolicitudVacacion $solicitud)
    {
        $modelo = new SolicitudVacacionResource($solicitud);
        return response()->json(compact('modelo'));
    }

    /**
     * La función "descuentos_permiso" calcula la duración total de los permisos de vacaciones de un
     * determinado empleado.
     *
     * @param Request $request El parámetro es una instancia de la clase Request, que se utiliza
     * para recuperar datos de la solicitud HTTP. En este caso, se utiliza para recuperar el valor del
     * parámetro "empleado" de la solicitud.
     *
     */
//    public function descuentos_permiso(Request $request)
//    {
//        return PermisoEmpleado::where('empleado_id', $request->empleado)->where('cargo_vacaciones', 1)
//            ->selectRaw('SUM(TIMESTAMPDIFF(HOUR, fecha_hora_inicio, fecha_hora_fin)) as duracion')
//            ->first();
//    }

    /**
     * La función actualiza un modelo de Vacaciones con los datos validados de la solicitud y devuelve
     * una respuesta JSON con un mensaje y el modelo actualizado.
     *
     * @param SolicitudVacacionRequest $request El parámetro es una instancia de la clase VacacionRequest,
     * que se utiliza para validar y recuperar los datos de la solicitud HTTP.
     * @param SolicitudVacacion $solicitud
     * @return JsonResponse respuesta JSON que contiene las variables 'mensaje' y 'modelo'.
     * @throws Throwable
     */
    public function update(SolicitudVacacionRequest $request, SolicitudVacacion $solicitud)
    {
        $autorizacion_anterior = $solicitud->autorizacion_id;

        $datos = $request->validated();
        $solicitud->update($datos);
        if ($solicitud->autorizacion_id !== $autorizacion_anterior && $solicitud->autorizacion_id === Autorizacion::APROBADO_ID) {
            $this->vacacionService->registrarDiasVacaciones($solicitud->empleado_id, $solicitud->periodo_id, $solicitud, $solicitud->fecha_inicio, $solicitud->fecha_fin);
        }
        event(new SolicitudVacacionEvent($solicitud));
        $modelo = new SolicitudVacacionResource($solicitud);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * La función destruye un objeto Vacaciones y devuelve una respuesta JSON.
     *
     * @return JsonResponse respuesta JSON que contiene el objeto Vacaciones eliminado.
     * @throws ValidationException
     */
    public function destroy()
    {
        throw ValidationException::withMessages([Utils::metodoNoDesarrollado()]);
    }

    /**
     * @throws ValidationException
     */
    public function imprimir(SolicitudVacacion $vacacion)
    {
        $configuracion = ConfiguracionGeneral::first();
        $resource = new SolicitudVacacionResource($vacacion);
        try {
            $pdf = Pdf::loadView('recursos-humanos.nomina_permisos.solicitud_vacacion', [
                'configuracion' => $configuracion,
                'vacacion' => $resource->resolve(),
                'empleado' => Empleado::with('departamento', 'grupo')->find($vacacion->empleado_id),
                'autorizador' => Empleado::find($vacacion->autorizador_id),
                'reemplazo' => Empleado::find($vacacion->reemplazo_id),
            ]);
            $pdf->setPaper('A4');
            $pdf->render();
            return $pdf->output();
        } catch (Throwable $th) {
            Log::channel('testing')->error('Log', ['ERROR en el try-catch global del metodo imprimir de VacacionController::imprimir', $th->getMessage(), $th->getLine()]);
            throw ValidationException::withMessages(['error' => Utils::obtenerMensajeError($th, 'No se puede imprimir el pdf: ')]);
        }
    }

    public function derechoVacaciones(int $id)
    {
        $dias = 0;
        $results = [];
        // El id recibido es el identificador de empleado
        // Entonces aquí se obtiene si el empleado tiene derecho a vacaciones y cuantos días
        $vacaciones = Vacacion::where('empleado_id', $id)
            ->where('opto_pago', false)->where('completadas', false)->get();

        if ($vacaciones->count() > 0) {
            foreach ($vacaciones as $vacacion) {
//            Log::channel('testing')->info('Log', ['Vacacion', $vacacion]);
                $dias += $vacacion->dias - $vacacion->detalles()->sum('dias_utilizados');
                $row['periodo'] = $vacacion->periodo->nombre;
//                $row['dias_disponibles'] = $vacacion->dias - $vacacion->detalles()->sum('dias_utilizados');
                $row['dias_disponibles'] = VacacionService::calcularDiasDeVacacionesPeriodoSeleccionado($vacacion);
                $results[] = $row;
            }
        } else {
            //Si no hay vacaciones se devuelve los días transcurridos para las vacaciones
            if (!Vacacion::where('empleado_id', $id)->exists()) {
                $empleado = Empleado::find($id);
                $fechaIngreso = Carbon::parse($empleado->fecha_ingreso);
                $periodo = Periodo::where('nombre', 'LIKE', $fechaIngreso->year . '%')->first();
                $row['periodo'] = $periodo->nombre;
                $row['dias_disponibles'] = VacacionService::calcularDiasDeVacacionEmpleadoNuevo($empleado);
            } else {
                //Significa que si hay vacaciones para el empleado pero que seguramente ya estan completadas
                $vacacion = Vacacion::where('empleado_id', $id)->orderBy('created_at', 'desc')->first();
//                Log::channel('testing')->info('Log', ['Ultima vacacion', $vacacion]);
//                Log::channel('testing')->info('Log', ['Ultima periodo', $vacacion->periodo->nombre]);
                $ultimo_anio = explode('-', $vacacion->periodo->nombre)[1];
                $periodo = Periodo::where('nombre', 'LIKE', $ultimo_anio . '%')->first();
                $row['periodo'] = $periodo->nombre;
                $row['dias_disponibles'] = VacacionService::calcularDiasDeVacacionEmpleadoAntiguo($vacacion->empleado, $ultimo_anio);
            }
            $results[] = $row;
        }
        return response()->json(compact('results', 'dias'));
    }

    /**
     * @throws ValidationException
     */
    public function anular(Request $request, SolicitudVacacion $solicitud)
    {
//        Log::channel('testing')->info('Log', ['Request', $request->all()]);
//        Log::channel('testing')->info('Log', ['solicitud', $solicitud]);

        $request->validate(['motivo' => ['required', 'string']]);
        try {
            // Buscamos el registro de autorizacion correspondiente al valor de ANULADO
            // Aqui se anula con autorizacion_id=5 o según como este en la bd
            $autorizacion = Autorizacion::where('nombre', Autorizacion::ANULADO)->first();
            if(!$autorizacion) throw new Exception('No se encuentra un registro de autorizacion con nombre ANULADO para continuar con la operación, consulte con el administrador del sistema.');
            // Actualizamos la solicitud de vacación
            $solicitud->autorizacion_id = $autorizacion->id;
            $solicitud->motivo_anulacion = $request->motivo;
            $solicitud->save();
            // Se verifica en el registro de vacaciones y se quita los dias y se vuelve su estado a no completado segun corresponda
            $this->vacacionService->anularDiasVacaciones($solicitud->empleado_id, $solicitud->periodo_id, $solicitud);

            $modelo = new SolicitudVacacionResource($solicitud);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        } catch (Throwable $th) {
            throw Utils::obtenerMensajeErrorLanzable($th);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

}
