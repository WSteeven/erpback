<?php

namespace App\Http\Controllers;

use App\Http\Requests\ComprobanteRequest;
use App\Http\Resources\ComprobanteResource;
use App\Models\Comprobante;
use App\Models\DetalleProductoTransaccion;
use App\Models\Inventario;
use App\Models\MaterialEmpleado;
use App\Models\MaterialEmpleadoTarea;
use App\Models\TransaccionBodega;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\Config\EstadosTransacciones;
use Src\Config\MotivosTransaccionesBodega;
use Src\Shared\Utils;
use Throwable;

class ComprobanteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = Comprobante::all();
        $results = ComprobanteResource::collection($results);
        return response()->json(compact('results'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param ComprobanteRequest $request
     * @param int $comprobante
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(ComprobanteRequest $request, int $comprobante)
    {
        try {
            $comprobante = Comprobante::where('transaccion_id', $comprobante)->first();
            // Log::channel('testing')->info('Log', ['[Despues de update] El comprobante a modificar es:', $comprobante]);
            if (!$comprobante->firmada) {
                DB::beginTransaction();
                $datos = $request->validated();
                $comprobante->update($datos);
                $comprobante->refresh();

                if ($comprobante->firmada) {
                    $transaccion = TransaccionBodega::find($comprobante->transaccion_id);
                    $transaccion->estado_id = EstadosTransacciones::COMPLETA;
                    $transaccion->save();
                    $transaccion->latestNotificacion()->update(['leida' => true]);
                    $es_egreso_liquidacion_materiales = TransaccionBodega::verificarEgresoLiquidacionMateriales($transaccion->motivo_id, $transaccion->motivo->tipo_transaccion_id, MotivosTransaccionesBodega::egresoLiquidacionMateriales);
                    $no_genera_comprobante = TransaccionBodega::verificarMotivosEgreso($transaccion->motivo_id);
                    if (!$es_egreso_liquidacion_materiales && !$no_genera_comprobante) TransaccionBodega::asignarMateriales($transaccion);
                }
                DB::commit();
            } else throw new Exception('Transacción ya fue firmada');

            $modelo = new ComprobanteResource($comprobante);
            $mensaje = 'Comprobante actualizado correctamente';
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Throwable $th) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($th, 'No se pudo completar la operación');
        }
    }

    /**
     * Actualizar parcial la recepcion del egreso.
     *
     * @param Request $request
     * @param TransaccionBodega $transaccion
     * @return JsonResponse
     * @throws Throwable
     */
    public function comprobanteParcial(Request $request, TransaccionBodega $transaccion)
    {
        try {
            DB::beginTransaction();

            foreach ($request->transaccion['listadoProductosTransaccion'] as $item) {
                $detalle = DetalleProductoTransaccion::where('inventario_id', $item['id'])->where('transaccion_id', $transaccion->id)->first();
                if ($detalle) {
                    $detalle->recibido += $item['recibido'];
                    $detalle->save();
                    $item_inventario = Inventario::find($item['id']);
                    if ($item_inventario) {
                        if ($transaccion->tarea_id) {
                            MaterialEmpleadoTarea::cargarMaterialEmpleadoTarea($item_inventario->detalle_id, $transaccion->responsable_id, $transaccion->tarea_id, $item['recibido'], $transaccion->cliente_id, $transaccion->proyecto_id, $transaccion->etapa_id);
                        } else {
                            MaterialEmpleado::cargarMaterialEmpleado($item_inventario->detalle_id, $transaccion->responsable_id, $item['recibido'], $transaccion->cliente_id);
                        }
                    } else {
                        throw new Exception('No se encontró el item de inventario');
                    }
                } else {
                    throw new Exception('No se encontró un detalle');
                }
            }

            if (Comprobante::verificarEgresoCompletado($transaccion->id)) { //se completó el egreso
                $transaccion->estado_id = EstadosTransacciones::COMPLETA;
                $transaccion->observacion_est = $request->observacion;
                $transaccion->save();

                $comprobante = Comprobante::where('transaccion_id', $transaccion->id)->first();
                $comprobante->estado = TransaccionBodega::ACEPTADA;
                $comprobante->observacion = $request->observacion;
                $comprobante->firmada = !$comprobante->firmada;
                $comprobante->save();
            } else {
                $transaccion->estado_id = EstadosTransacciones::PARCIAL;
                $transaccion->observacion_est = $request->observacion;
                $transaccion->save();


                $comprobante = Comprobante::where('transaccion_id', $transaccion->id)->first();
                $comprobante->estado = TransaccionBodega::PARCIAL;
                $transaccion->observacion_est = $request->observacion;
                $comprobante->save();
            }
            $modelo = new ComprobanteResource($comprobante);
            $mensaje = 'Comprobante actualizado correctamente';
            // throw new Exception('Actualizar comprobante parcial');
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw ValidationException::withMessages(['error' => [$e->getMessage() . '. ' . $e->getLine()]]);
        }

        return response()->json(compact('mensaje', 'modelo'));
    }
}
