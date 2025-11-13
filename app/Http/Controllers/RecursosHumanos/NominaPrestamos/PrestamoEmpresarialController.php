<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Exports\RecursosHumanos\ReportePrestamosEmpresarialesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\NominaPrestamos\PrestamoEmpresarialRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\PrestamoEmpresarialResource;
use App\Models\ConfiguracionGeneral;
use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\PlazoPrestamoEmpresarial;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoEmpresarial;
use App\Models\RecursosHumanos\NominaPrestamos\RolPago;
use App\Models\RecursosHumanos\NominaPrestamos\SolicitudPrestamoEmpresarial;
use App\Models\User;
use Carbon\Carbon;
use Eloquent;
use Excel;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Pdf;
use Src\Shared\Utils;
use Throwable;

class PrestamoEmpresarialController extends Controller
{
    private string $entidad = 'Prestamo Empresarial';

    public function __construct()
    {
        $this->middleware('can:puede.ver.prestamo_empresarial')->only('index', 'show');
        $this->middleware('can:puede.crear.prestamo_empresarial')->only('store');
        $this->middleware('can:puede.editar.prestamo_empresarial')->only('update');
        $this->middleware('can:puede.eliminar.prestamo_empresarial')->only('destroy');
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole([User::ROL_GERENTE, User::ROL_RECURSOS_HUMANOS, User::ROL_CONTABILIDAD, User::ROL_ADMINISTRADOR])) {
            $results = PrestamoEmpresarial::ignoreRequest(['campos'])->filter()->orderBy('id', 'desc')->get();
        } else {
            $results = PrestamoEmpresarial::where('solicitante', $user->empleado->id)->ignoreRequest(['campos'])->filter()->get();
        }
        $results = PrestamoEmpresarialResource::collection($results);
        return response()->json(compact('results'));
    }

    public function show(PrestamoEmpresarial $prestamo)
    {
        $modelo = new PrestamoEmpresarialResource($prestamo);
        return response()->json(compact('modelo'));
    }

    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function store(PrestamoEmpresarialRequest $request)
    {
        // Log::channel('testing')->info('Log', ['Datos', $request->all()]);
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $this->validarSolicitudNoGestionada($request);
            $this->validarMontoVSCuotas($datos['monto'], $datos['plazos']);
//            Log::channel('testing')->info('Log', ['Datos validados', $datos]);

            $prestamo = PrestamoEmpresarial::create($datos);
            PlazoPrestamoEmpresarial::actualizarCuotasPrestamo($prestamo, $datos['plazos']);
//            $this->crearPlazos($prestamo, $request->plazos);
            if ($prestamo->id_solicitud_prestamo_empresarial) {
                $prestamo->solicitudPrestamoEmpresarial()->update(['gestionada' => true]);
            }
            DB::commit();

            $modelo = new PrestamoEmpresarialResource($prestamo);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al generar préstamo' => [$e->getMessage()],
            ]);
        }
    }

    /**
     * Actualiza el estado del préstamo empresarial según el valor pendiente de pago.
     * Si no hay valores pendientes, lo marca como finalizado.
     * @throws Throwable
     */
    public function update(PrestamoEmpresarialRequest $request, PrestamoEmpresarial $prestamo)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $this->validarMontoVSCuotas($datos['monto'], $datos['plazos']);
            $this->validarMontoVSCuotas($datos['monto'], $datos['plazos'], 'valor_a_pagar', true);
            $prestamo->update($datos);

            PlazoPrestamoEmpresarial::actualizarCuotasPrestamo($prestamo->refresh(), $datos['plazos']);
            $this->actualizarPrestamo($prestamo->refresh());
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
        $modelo = new PrestamoEmpresarialResource($prestamo->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Elimina un préstamo empresarial.
     * Retorna el modelo eliminado y un mensaje en formato JSON.
     * @param PrestamoEmpresarial $prestamo
     * @return JsonResponse
     */
    public function destroy(PrestamoEmpresarial $prestamo)
    {
        $prestamo->delete();
        $modelo = $prestamo;
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Actualiza el estado del préstamo empresarial según el valor pendiente de pago.
     * Si no hay valores pendientes, lo marca como finalizado.
     * @param PrestamoEmpresarial $prestamo
     * @return JsonResponse
     */
    public function actualizarPrestamo(PrestamoEmpresarial $prestamo)
    {
        $plazos = PlazoPrestamoEmpresarial::where('id_prestamo_empresarial', $prestamo->id)->get();
        $suma_valor_pendiente = $plazos->sum('valor_a_pagar');
        if ($suma_valor_pendiente == 0) {
            // se actualiza el estado de prestamo para que pase de activo a finalizado
            $prestamo->estado = PrestamoEmpresarial::FINALIZADO;
            $prestamo->save();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        } else
            $mensaje = 'Se actualizó el préstamo, pero aún tiene valores pendientes de pagar';
        return response()->json(compact('mensaje'));
    }

    /**
     * Segun los permisos del front, solo el ADMINISTRADOR puede deshabilitar un Prestamo (eliminar).
     * Tenga en cuenta que solo debe eliminar prestamos que aún no se han marcado como pagados ninguna de sus cuotas.
     * @param Request $request
     * @return JsonResponse
     */
    public function deshabilitarPrestamo(Request $request)
    {
        $prestamo_empresarial = PrestamoEmpresarial::where('id', $request->id)->first();
        $prestamo_empresarial->motivo = $request->motivo;
        $prestamo_empresarial->estado = PrestamoEmpresarial::INACTIVO;
        $prestamo_empresarial->save();
        $prestamo_empresarial->plazo_prestamo_empresarial_info()->update(['estado' => false]);
        $modelo = $prestamo_empresarial;
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Obtiene la suma de valores pendientes de pago para un empleado en un mes y año específico
     * @param Request $request
     * @return JsonResponse
     */
    public function obtenerPrestamoEmpleado(Request $request)
    {
        list($mes, $anio) = explode('-', $request->mes);
        $results = PlazoPrestamoEmpresarial::
        join('prestamo_empresarial', 'plazo_prestamo_empresarial.id_prestamo_empresarial', '=', 'prestamo_empresarial.id')
            ->where('prestamo_empresarial.solicitante', $request->empleado)
            ->whereYear('fecha_vencimiento', $anio)
            ->whereMonth('fecha_vencimiento', $mes)
            ->where('pago_cuota', 0)
            ->sum('valor_a_pagar');

        return response()->json(compact('results'));
    }

    /**
     * Calcula la cantidad y valores de las cuotas de un préstamo empresarial
     * según la fecha de inicio de cobro, monto y plazo.
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function calcularCantidadCuotas(Request $request)
    {
        $mes_inicia_cobro = Carbon::parse($request->fecha_inicio_cobro)->endOfMonth();
        // si el mes de inicio de cobro es menor a la fecha de descuento, se lanza un error
        if ($mes_inicia_cobro->lt(Carbon::now())) throw new Exception('La fecha del préstamo debe ser menor al mes de inicio del cobro');

        $cuotas = $this->obtenerCuotasPrestamoEmpresarial($mes_inicia_cobro, $request->monto, $request->plazo);

        return response()->json(compact('cuotas'));
    }

    /**
     * Genera el arreglo de cuotas para un préstamo empresarial, ajustando la primera cuota si hay diferencia por redondeo.
     * @param Carbon $mesIniciaCobro
     * @param float $valor
     * @param int|null $cantidadCuotas
     * @return array
     */
    private function obtenerCuotasPrestamoEmpresarial(Carbon $mesIniciaCobro, float $valor = 0, ?int $cantidadCuotas = 1)
    {
        if ($valor <= 0) return [];
        $cuotas = [];

        //Redondear al centavo base
        $valorCuotaBase = round($valor / $cantidadCuotas, 2);

        // Calcular el total con cuota base
        $totalCuotaBase = $valorCuotaBase * $cantidadCuotas;

        // Determinar diferencia a ajustar (positiva o negativa)
        $diferencia = round($valor - $totalCuotaBase, 2);

        for ($i = 1; $i <= $cantidadCuotas; $i++) {
            $mes = $mesIniciaCobro->copy()->addMonthsNoOverflow($i - 1);

            // Ajustamos la primera cuota con la diferencia (si existe)
            $ajuste = 0;
            if ($diferencia !== 0.0) {
                $ajuste = $diferencia;
                $diferencia = 0.0; // solo se ajusta una vez
            }

            $cuotas[] = [
                'id' => $i,
                'num_cuota' => $i,
                'fecha_vencimiento' => $mes->endOfMonth()->format('Y-m-d'),
                'fecha_pago' => null,
                'valor_cuota' => round($valorCuotaBase + $ajuste, 2),
                'valor_pagado' => 0,
                'valor_a_pagar' => round($valorCuotaBase + $ajuste, 2),
                'pago_cuota' => false,
                'modificada' => false,
                'comentario' => null,
            ];
        }

        return $cuotas;
    }

    /**
     * Verifica si la solicitud de préstamo empresarial ya ha sido gestionada previamente.
     * @throws Exception si la solicitud ya ha sido gestionada
     */
    private function validarSolicitudNoGestionada($request)
    {
        if ($request->id_solicitud_prestamo_empresarial) {
            if (SolicitudPrestamoEmpresarial::where('id', $request->id_solicitud_prestamo_empresarial)->where('gestionada', true)->exists())
                throw new Exception("La solicitud de préstamo que intentas registrar ya ha sido gestionada en un préstamo anterior. Verifica los datos");
        }
    }

    /**
     * Verifica si la solicitud de préstamo empresarial ya ha sido gestionada previamente.
     * @throws Exception si la solicitud ya ha sido gestionada
     */
    private function validarMontoVSCuotas(float $monto, array $cuotas, $clave = 'valor_cuota', $incluirPagadas = false)
    {
        $sumaCuotas = array_sum(array_column($cuotas, $clave));

        if (!$incluirPagadas) {
            if (round($sumaCuotas, 2) !== round($monto, 2))
                throw new Exception("La suma de las cuotas ($sumaCuotas) no coincide con el monto del préstamo ($monto). Por favor, verifica los datos ingresados.");
        } else {
            $sumaValoresPagadosCuotas = array_sum(array_column($cuotas, 'valor_pagado'));
            $suma = $sumaValoresPagadosCuotas + $sumaCuotas;
            if (round($suma, 2) !== round($monto, 2))
                throw new Exception("La suma de las cuotas ($suma) no coincide con el monto del préstamo ($monto). Por favor, verifica los datos ingresados.");
        }

    }

    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function aplazarCuotaPrestamo(Request $request, PlazoPrestamoEmpresarial $cuota)
    {
//        Log::channel('testing')->info('Log', ['Request', $request->all()]);
//        Log::channel('testing')->info('Log', ['Cuota', $cuota]);
        try {
            DB::beginTransaction();

            $ultimaCuota = PlazoPrestamoEmpresarial::where('id_prestamo_empresarial', $cuota->id_prestamo_empresarial)
                ->orderBy('fecha_vencimiento', 'desc')->first();

            // Buscamos si la cuota está registrada en un rol de pago del mes de vencimiento
            //sacamos la fecha de vencimiento
            $fechaVencimiento = Carbon::parse($cuota->fecha_vencimiento);
            $rolEmpleado = RolPago::where('empleado_id', $cuota->prestamo->solicitante)
                ->where('mes', $fechaVencimiento->format('m-Y'))->first();
            if ($rolEmpleado)
                if ($rolEmpleado->prestamo_empresarial > 0) {
                    // Tenemos que descontar el valor del préstamo del rol de pago ya que se aplazará la cuota
                    $rolEmpleado->prestamo_empresarial -= $cuota->valor_a_pagar;
                    $rolEmpleado->save();
                }
            // Si no existe el rol de pago, no hacemos nada, ya que no hay descuento que hacer
            $ultimoMes = Carbon::parse($ultimaCuota->fecha_vencimiento);
            $cuota->fecha_vencimiento = $ultimoMes->addMonth()->endOfMonth()->format('Y-m-d');
            $cuota->comentario = $request->comentario;
            $cuota->save();
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($e);
        }

        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'cuota'));
    }

    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function pagarCuotaPrestamo(Request $request, PlazoPrestamoEmpresarial $cuota)
    {
        try {
            if ($request->monto > $cuota->valor_a_pagar) {
                throw new Exception('El monto ingresado excede el valor pendiente de pago. Máximo_permitido: ' . $cuota->valor_a_pagar, 422);
            }
            DB::beginTransaction();
            $cuota->fecha_pago = Carbon::now()->format('Y-m-d');
            $cuota->valor_pagado += $request->monto;
            $cuota->valor_a_pagar = round($cuota->valor_a_pagar - $request->monto, 2);

            if ($cuota->valor_a_pagar <= 0) {
                $cuota->pago_cuota = true;
                $cuota->valor_a_pagar = 0;
            }
            $cuota->save();
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'cuota'));
    }

    /**
     * @throws ValidationException
     */
    public function reportes(Request $request)
    {
        try {
            $configuracion = ConfiguracionGeneral::first();
            $results = $this->reporte($request);
            switch ($request->accion) {
                case 'excel':
                    return Excel::download(new ReportePrestamosEmpresarialesExport($results, $configuracion), 'reporte.xlsx');
                case 'pdf':
                    $reporte = $results;
                    $pdf = Pdf::loadView('recursos-humanos/nomina_prestamos/reporte_prestamos_empresariales', compact(['reporte', 'configuracion']));
                    $pdf->setPaper('A4', 'landscape');
                    $pdf->render();
                    return $pdf->output();
                default:
                    // Nothing, return the data
            }
        } catch (Throwable $ex) {
            Log::channel('testing')->error('Log', ['PrestamoEmpresarialController->reportes', $ex->getLine(), $ex->getMessage(), $ex]);
            throw Utils::obtenerMensajeErrorLanzable($ex, 'No se pudo obtener el reporte');
        }
        return response()->json(compact('results'));
    }

    private function reporte(Request $request)
    {
        $query = PrestamoEmpresarial::where('estado', $request->estado);
        $prestamos = $request->todos? $query->get() : $query->where('solicitante', $request->empleado)->get();
        return $this->mapearReporte($prestamos);
    }

    protected function mapearReporte($prestamos)
    {
        $results = [];
        $row = [];
        foreach ($prestamos as $prestamo) {
            $row['id'] = $prestamo->id;
            $row['empleado'] = Empleado::extraerApellidosNombres($prestamo->empleado);
            $row['fecha'] = $prestamo->fecha;
            $row['monto']= round($prestamo->monto,2);
            $row['periodo']= $prestamo->plazo;
            $row['plazo']= $prestamo->plazo;
            $row['estado']= $prestamo->estado;
            $row['motivo']= $prestamo->estado;
            $row['cantidad_cuotas'] = $prestamo->plazos->count();
            $row['monto_pagado']= round($prestamo->plazos->sum('valor_pagado'),2);
            $row['monto_pendiente']= round($prestamo->plazos->sum('valor_a_pagar'),2);
            $row['valor_cuota']= round($prestamo->plazos->max('valor_cuota'),2);
            $row['fecha_primera_cuota']= $prestamo->plazos->sortBy('fecha_vencimiento')->first()->fecha_vencimiento;
            $row['fecha_ultima_cuota']= $prestamo->plazos->sortByDesc('fecha_vencimiento')->first()->fecha_vencimiento;


            $results[]=$row;
        }

        return $results;
    }


}
