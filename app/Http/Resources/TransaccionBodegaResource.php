<?php

namespace App\Http\Resources;

use App\Models\TransaccionBodega;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransaccionBodegaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $controller_method = $request->route()->getActionMethod();
        $detalles = TransaccionBodega::listadoProductos($this->id);
        $comprobante = TransaccionBodega::obtenerComprobante($this->id);

        // Log::channel('testing')->info('Log', ['controller method?:', $controller_method]);

        $modelo = [
            'id' => $this->id,

            'autorizacion' =>  $this->autorizacion?->nombre,
            'cliente' => $this->cliente ? $this->cliente->empresa->razon_social : null,
            'cliente_id' => $this->cliente_id,
            'created_at' => date('d/m/Y', strtotime($this->created_at)),
            'estado_comprobante' => $comprobante?->estado,
            'estado' => $this->estado->nombre,
            'firmada' => $comprobante?->firmada,
            'justificacion' => $this->justificacion,
            'motivo' => $this->motivo?->nombre,
            // 'observacion_aut' =>  $this->observacion_aut,
            // 'observacion_est' =>  $this->observacion_est,
            'pedido' => $this->pedido_id,
            'per_atiende' => is_null($this->atiende) ? '' : $this->atiende->nombres . ' ' . $this->atiende->apellidos,
            'per_autoriza' => $this->autoriza->nombres . ' ' . $this->autoriza->apellidos,
            'per_retira_id' => $this->retira?->id,
            'per_retira' => is_null($this->retira) ? '' : $this->retira->nombres . ' ' . $this->retira->apellidos,
            'proveedor' => $this->proveedor,
            'responsable_id' => $this->responsable_id,
            'responsable' => $this->responsable ? $this->responsable->nombres . ' ' . $this->responsable->apellidos : null,
            'solicitante_id' => $this->solicitante_id,
            'solicitante' => $this->solicitante ? $this->solicitante->nombres . ' ' . $this->solicitante->apellidos : 'N/A',
            'sucursal_id' => $this->sucursal_id,
            'sucursal' => $this->sucursal?->lugar,
            'tiene_obs_autorizacion' => !is_null($this->observacion_aut),
            'tiene_obs_estado' => !is_null($this->observacion_est),
            'tiene_pedido' => (bool)$this->pedido_id,
            'transferencia' => $this->transferencia_id,
            // 'aviso_liquidacion_cliente'=>TransaccionBodega::verificarEgresoLiquidacionMateriales($this->motivo_id, $this->motivo->tipo_transaccion_id, MotivosTransaccionesBodega::egresoLiquidacionMateriales),
            'comprobante' => $this->comprobante,
            // 'comprobante'=>$comprobante,
            'devolucion' => $this->devolucion_id,
            // 'fecha_limite' => $this->fecha_lximite,
            // 'listadoProductosTransaccion' => $detalles,
            // 'retira_tercero'=>$this->tarea?true:false,
            'tarea_codigo' => $this->tarea ? $this->tarea->codigo_tarea : null,
            'tarea' => $this->tarea ? $this->tarea->titulo : null,
            // 'tipo' => $this->motivo?->tipo?->nombre,
            //variables auxiliares
            'codigo_permiso_traslado' => $this->codigo_permiso_traslado,
            'proveedor_id' => $this->proveedor_id,
            'fecha_compra' => $this->fecha_compra,
        ];

        if ($controller_method == 'show') {
            $modelo['autorizacion'] = $this->autorizacion_id;
            $modelo['estado'] = $this->estado_id;
            $modelo['solicitante'] = $this->solicitante_id;
            $modelo['devolucion'] = $this->devolucion_id;
            $modelo['pedido'] = $this->pedido_id;
            $modelo['responsable'] = $this->responsable_id;
            $modelo['transferencia'] = $this->transferencia_id;
            $modelo['tipo'] = $this->tipo_id;
            $modelo['motivo'] = $this->motivo_id;
            $modelo['nombre_motivo'] = $this->motivo->nombre;
            $modelo['tarea'] = $this->tarea_id;
            $modelo['cliente'] = $this->cliente_id;
            $modelo['cliente_id'] = $this->cliente_id;
            $modelo['sucursal'] = $this->sucursal_id;
            $modelo['per_autoriza'] = $this->per_autoriza_id;
            $modelo['per_atiende'] = $this->per_atiende_id;
            $modelo['per_retira'] = $this->per_retira_id;
            $modelo['created_at'] = date('d/m/Y', strtotime($this->created_at));
            $modelo['listadoProductosTransaccion'] = $detalles;
            $modelo['es_tarea'] = (bool)$this->tarea;
            $modelo['es_transferencia'] = (bool)$this->transferencia_id;
            $modelo['observacion_est'] =  $this->observacion_est;
        }

        return $modelo;
    }
}
