<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\NominaPrestamos\PrestamoEmpresarialRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\PrestamoEmpresarialResource;
use App\Models\RecursosHumanos\NominaPrestamos\PlazoPrestamoEmpresarial;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoEmpresarial;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $prestamo = PrestamoEmpresarial::create($datos);
            $this->crearPlazos($prestamo, $request->plazos);
            $modelo = new PrestamoEmpresarialResource($prestamo);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al generra prestamo' => [$e->getMessage()],
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

    public function crearPlazos(PrestamoEmpresarial $prestamo, $plazos)
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
    }

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
}
