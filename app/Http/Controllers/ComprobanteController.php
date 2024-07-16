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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\Config\EstadosTransacciones;
use Src\Config\MotivosTransaccionesBodega;

class ComprobanteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = Comprobante::all();
        $results = ComprobanteResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comprobante  $comprobante
     * @return \Illuminate\Http\Response
     */
    public function show(Comprobante $comprobante)
    {
        Log::channel('testing')->info('Log', ['Show de comprobante:', $comprobante]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comprobante  $comprobante
     * @return \Illuminate\Http\Response
     */
    public function update(ComprobanteRequest $request, int $comprobante)
    {
        // Log::channel('testing')->info('Log', ['[Update] El comprobante a modificar es:', $request->all(), $comprobante]);
        $comprobante = Comprobante::where('transaccion_id', $comprobante)->first();
        // Log::channel('testing')->info('Log', ['[Despues de update] El comprobante a modificar es:', $comprobante]);
        if (!$comprobante->firmada) {
            $datos = $request->validated();
            $comprobante->update($datos);
            $comprobante->refresh();

            if ($comprobante->firmada) {
                $transaccion = TransaccionBodega::find($comprobante->transaccion_id);
                $transaccion->estado_id = EstadosTransacciones::COMPLETA;
                $transaccion->save();
                $transaccion->latestNotificacion()->update(['leida' => true]);
                $esEgresoLiquidacionMateriales = TransaccionBodega::verificarEgresoLiquidacionMateriales($transaccion->motivo_id, $transaccion->motivo->tipo_transaccion_id, MotivosTransaccionesBodega::egresoLiquidacionMateriales);
                $noGeneraComprobante = TransaccionBodega::verificarMotivosEgreso($transaccion->motivo_id);
                if (!$esEgresoLiquidacionMateriales && !$noGeneraComprobante) TransaccionBodega::asignarMateriales($transaccion);
            }
        } else throw new Exception('Transacción ya fue firmada');

        $modelo = new ComprobanteResource($comprobante);
        $mensaje = 'Comprobante actualizado correctamente';
        return response()->json(compact('mensaje', 'modelo'));
    }
    /**
     * Actualizar parcial la recepcion del egreso.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TransaccionBodega  $transaccion
     * @return \Illuminate\Http\Response
     */
    public function comprobanteParcial(Request $request, TransaccionBodega $transaccion)
    {
        try {
            Log::channel('testing')->info('Log', ['request:', $request->all()]);
            DB::beginTransaction();

            foreach ($request->transaccion['listadoProductosTransaccion'] as $item) {
                $detalle = DetalleProductoTransaccion::where('inventario_id', $item['id'])->where('transaccion_id', $transaccion->id)->first();
                if ($detalle) {
                    $detalle->recibido += $item['recibido'];
                    $detalle->save();
                    $itemInventario = Inventario::find($item['id']);
                    if ($itemInventario) {
                        if ($transaccion->tarea_id) {
                            MaterialEmpleadoTarea::cargarMaterialEmpleadoTarea($itemInventario->detalle_id, $transaccion->responsable_id, $transaccion->tarea_id, $item['recibido'], $transaccion->cliente_id, $transaccion->proyecto_id, $transaccion->etapa_id);
                        } else {
                            MaterialEmpleado::cargarMaterialEmpleado($itemInventario->detalle_id, $transaccion->responsable_id, $item['recibido'], $transaccion->cliente_id);
                        }
                    } else {
                        throw new Exception('No se encontró el item de inventario');
                    }
                } else {
                    throw new Exception('No se encontró un detalle');
                }
            }

            if (Comprobante::verificarEgresoCompletado($transaccion->id)) { //se completo el egreso 
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
            Log::channel('testing')->info('Log', ['error:', $e]);
            DB::rollback();
            throw ValidationException::withMessages(['error' => [$e->getMessage() . '. ' . $e->getLine()]]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . $e->getLine()], 422);
            return response()->json(['mensaje' => $e], 422);
        }

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comprobante  $comprobante
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comprobante $comprobante)
    {
        //
    }
}
