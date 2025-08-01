<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\NominaPrestamos\PrestamoEmpresarialRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\PrestamoEmpresarialResource;
use App\Models\RecursosHumanos\NominaPrestamos\PlazoPrestamoEmpresarial;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoEmpresarial;
use App\Models\RecursosHumanos\NominaPrestamos\SolicitudPrestamoEmpresarial;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
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
        Log::channel('testing')->info('Log', ['Datos', $request->all()]);
        try {
            $this->validarSolicitudNoGestionada($request);

            DB::beginTransaction();
            $datos = $request->validated();
            Log::channel('testing')->info('Log', ['Datos validados', $datos]);

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

    public function update(PrestamoEmpresarialRequest $request, PrestamoEmpresarial $prestamo)
    {
        $datos = $request->validated();
        $prestamo->update($datos);
        $this->modificarPlazo($request->plazos);
        $this->actualizarPrestamo($prestamo);
        $modelo = new PrestamoEmpresarialResource($prestamo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function destroy(PrestamoEmpresarial $prestamo)
    {
        $prestamo->delete();
        $modelo = $prestamo;
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje', 'modelo'));
    }

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

    /*public function crearPlazos(PrestamoEmpresarial $prestamo, $plazos)
    {
        $plazos_actualizados = collect($plazos)->map(function ($plazo) use ($prestamo) {
            return [
                'id_prestamo_empresarial' => $prestamo->id,
                'pago_cuota' => false,
                'num_cuota' => $plazo['num_cuota'],
                'fecha_vencimiento' => $plazo['fecha_vencimiento'],
                'valor_cuota' => $plazo['valor_couta'] ?? $plazo['valor_cuota'] ?? 0,
                'valor_a_pagar' => $plazo['valor_cuota']

            ];
        })->toArray();
        PlazoPrestamoEmpresarial::insert($plazos_actualizados);
    }*/

    public function modificarPlazo($plazos)
    {
        // Crear un arreglo con los datos actualizados para cada plazo
        $plazos_actualizados = collect($plazos)->map(function ($plazo) {
            return [
                'id' => $plazo['id'],
                'pago_cuota' => $plazo['pago_couta'] ?? $plazo['pago_cuota'],
                'num_cuota' => $plazo['num_cuota'],
                'fecha_vencimiento' => $plazo['fecha_vencimiento'],
                'fecha_pago' => $plazo['fecha_pago'],
                'valor_pagado' => $plazo['valor_pagado'],
                'valor_a_pagar' => $plazo['valor_a_pagar'],
                'comentario' => $plazo['comentario']
            ];
        })->toArray();
        // Realizar la actualización masiva
        foreach ($plazos_actualizados as $plazo) {
            $id = $plazo['id'];
            PlazoPrestamoEmpresarial::where('id', $id)->update($plazo);
        }
    }

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
     * @throws Exception
     */
    public function calcularCantidadCuotas(Request $request)
    {
//        Log::channel('testing')->info('Log', ['Datos', $request->all()]);
        $mes_inicia_cobro = Carbon::parse($request->fecha_inicio_cobro)->endOfMonth();
        // si el mes de inicio de cobro es menor a la fecha de descuento, se lanza un error
        if ($mes_inicia_cobro->lt(Carbon::now())) throw new Exception('La fecha del préstamo debe ser menor al mes de inicio del cobro');

        $cuotas = $this->obtenerCuotasPrestamoEmpresarial($mes_inicia_cobro, $request->monto, $request->plazo);

        return response()->json(compact('cuotas'));
    }

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
                'comentario' => null,
            ];
        }

        return $cuotas;
    }

    /**
     * @throws Exception
     */
    private function validarSolicitudNoGestionada($request)
    {
        if ($request->id_solicitud_prestamo_empresarial) {
            if (SolicitudPrestamoEmpresarial::where('id', $request->id_solicitud_prestamo_empresarial)->where('gestionada', true)->exists())
                throw new Exception("La solicitud de préstamo que intentas registrar ya ha sido gestionada en un préstamo anterior. Verifica los datos");
        }
    }
}
