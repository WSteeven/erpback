<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Exports\RecursosHumanos\PlanesVacacionesExport;
use App\Exports\RecursosHumanos\VacacionesExport;
use App\Exports\VacacionesPendientesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\VacacionRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\VacacionResource;
use App\Models\ConfiguracionGeneral;
use App\Models\RecursosHumanos\NominaPrestamos\Vacacion;
use App\Models\RecursosHumanos\NominaPrestamos\ValorEmpleadoRolMensual;
use Carbon\Carbon;
use DB;
use Excel;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Pdf;
use Src\App\RecursosHumanos\NominaPrestamos\VacacionService;
use Src\Shared\Utils;
use Throwable;

class VacacionController extends Controller
{
    private string $entidad = "Vacacion";

    public function __construct()
    {
        $this->middleware('can:puede.ver.vacaciones')->only('index', 'show');
        $this->middleware('can:puede.editar.vacaciones')->only('update');
        $this->middleware('can:puede.eliminar.vacaciones')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        if (request('tipo')) {
            $results = match (request('tipo')) {
                'PENDIENTES' => Vacacion::where('completadas', false)->get(),
                'REALIZADAS' => Vacacion::where('completadas', true)->get(),
            };
        } else {
            $results = Vacacion::ignoreRequest(['tipo'])->filter()->get();
        }
        $results = VacacionResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function store(/* VacacionRequest $request */)
    {
        throw ValidationException::withMessages([Utils::metodoNoDesarrollado()]);

//        try {
//            DB::beginTransaction();
//            $datos = $request->validated();
//
//            $vacacion = Vacacion::create($datos);
//
//            $modelo = new VacacionResource($vacacion);
//            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
//            DB::commit();
//        } catch (Throwable $th) {
//            DB::rollBack();
//            throw Utils::obtenerMensajeErrorLanzable($th, 'Guardar Vacacion ' . $this->entidad);
//        }
//        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param Vacacion $vacacion
     * @return JsonResponse
     */
    public function show(Vacacion $vacacion)
    {
        $modelo = new VacacionResource($vacacion);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param VacacionRequest $request
     * @param Vacacion $vacacion
     * @return JsonResponse
     * @throws Throwable|ValidationException
     */
    public function update(VacacionRequest $request, Vacacion $vacacion)
    {
        $opto_pago_old = $vacacion->opto_pago;


        try {
            DB::beginTransaction();
            $datos = $request->validated();

            $vacacion->update($datos);
            // Verificamos si cambió el valor de opto_pago para lanzar el mecanismo de que ese pago se realice en Rol de Pagos
            if ($opto_pago_old != $vacacion->opto_pago && $vacacion->opto_pago) {
                // Se crea el registro que será tomado en el rol de pagos del mes selecionado por el usuario
                $vacacion->valoresRolMensualEmpleado()->create([
                    'tipo' => ValorEmpleadoRolMensual::INGRESO,
                    'mes' => $vacacion->mes_pago,
                    'empleado_id' => $vacacion->empleado_id,
                    'monto' => VacacionService::calcularMontoPagarVacaciones($vacacion),
                ]);
                // Se actualiza los detalles de la vacacion para saber que fueron pagados N dias
                $vacacion->detalles()->create([
                    'fecha_inicio' => Carbon::now(),
                    'fecha_fin' => Carbon::now(),
                    'dias_utilizados' => VacacionService::calcularDiasDeVacacionesPeriodoSeleccionado($vacacion),
                    'observacion' => 'Dias de Vacaciones pagadas al seleccionar la opcion opto pago'
                ]);
                $vacacion->completadas = true;
                $vacacion->save();
            }
            if ($vacacion->detalles()->sum('dias_utilizados') == $vacacion->dias) $vacacion->update(['completadas' => true]);

            $modelo = new VacacionResource($vacacion->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($th, 'Actualizar ' . $this->entidad);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * @throws ValidationException
     */
    public function reporteVacaciones(Request $request)
    {
        try {
            $configuracion = ConfiguracionGeneral::first();
            $results = VacacionService::reporte($request);
            switch ($request->accion) {
                case 'excel':
                    return match ($request->tipo) {
                        'PLAN_VACACIONES' => Excel::download(new PlanesVacacionesExport($results, $configuracion), 'reporte_planes_vacaciones.xlsx'),
                        'VACACIONES_PENDIENTES' => Excel::download(new VacacionesPendientesExport($results, $configuracion), 'reporte_vacaciones_pendientes.xlsx'),
                        default => Excel::download(new VacacionesExport($results, $configuracion), 'reporte_vacaciones.xlsx')
                    };
                case 'pdf':
                    $reporte = $results;
                    $pdf = match($request->tipo){
                        'PLAN_VACACIONES'=> Pdf::loadView('recursos-humanos/nomina_permisos/reporte_plan_vacaciones', compact(['reporte', 'configuracion'])),
                        default => Pdf::loadView('recursos-humanos/nomina_permisos/reporte_vacaciones', compact(['reporte', 'configuracion'])),
                    };
                    $pdf->setPaper('A4', 'landscape');
                    $pdf->render();
                    return $pdf->output();
                default:
                    // Nothing, return the data
            }
        } catch (Exception $ex) {
            Log::channel('testing')->error('Log', ['error en reporteVacaciones', $ex->getLine(), $ex->getMessage(), $ex]);
            throw Utils::obtenerMensajeErrorLanzable($ex, 'No se pudo obtener el reporte');
        }

        return response()->json(compact('results'));
    }


}
