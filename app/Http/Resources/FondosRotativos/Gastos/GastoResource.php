<?php

namespace App\Http\Resources\FondosRotativos\Gastos;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class GastoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $controller_method = $request->route()->getActionMethod();
        $modelo = [
            'id'=> $this->id,
            'fecha_viat' => $this->cambiar_fecha($this->fecha_viat),
            'lugar' => $this->id_lugar,
            'lugar_info' => $this->lugar_info->canton,
            'num_tarea' => $this->id_tarea == null ? 0 : $this->id_tarea,
            'subTarea' => $this->id_subtarea == null ? 0 : $this->id_subtarea,
            'subTarea_info' => $this->subtarea_info != null ? $this->subtarea_info->codigo_subtarea . ' - ' . $this->subtarea_info->titulo : 'Sin Subtarea',
            'tarea_info' =>  $this->tarea_info != null ? $this->tarea_info->codigo_tarea . ' - ' . $this->tarea_info->detalle : 'Sin Tarea',
            'tarea_cliente' =>  $this->tarea_info != null ? $this->tarea_info->codigo_tarea_cliente : 'Sin Tarea',
            'proyecto' => $this->id_proyecto != null ? $this->id_proyecto : 0,
            'proyecto_info' => $this->proyecto_info != null ? $this->proyecto_info->codigo_proyecto . ' - ' . $this->proyecto_info->nombre : 'Sin Proyecto',
            'ruc' => $this->ruc,
            'factura' => strlen($this->factura)>1?$this->factura:null,
            'aut_especial_user' => $this->aut_especial_user->nombres . ' ' . $this->aut_especial_user->apellidos,
            'aut_especial' => $this->aut_especial,
            'detalle_info' => $this->detalle_info->descripcion,
            'detalle_estado' => $this->detalle_estado,
            'sub_detalle_info' => $this->sub_detalle_info != null ? $this->subdetalle_info($this->sub_detalle_info) : '',
            'beneficiarios' => $this->beneficiario_info != null ? $this->beneficiario_info->pluck('empleado_id') : null,
            'beneficiarios_info' => $this->beneficiario_empleado_info($this->beneficiario_info),
            'sub_detalle' => $this->sub_detalle_info != null ? $this->sub_detalle_info->pluck('id') : null,
            'vehiculo' => $this->gasto_vehiculo_info != null ? $this->gasto_vehiculo_info->id_vehiculo : '',
            'placa' =>  $this->gasto_vehiculo_info != null ? $this->gasto_vehiculo_info->placa : '',
            'kilometraje' => $this->gasto_vehiculo_info != null ? $this->gasto_vehiculo_info->kilometraje : '',
            'detalle' => $this->detalle,
            'cantidad' => $this->cantidad,
            'valor_u' => $this->valor_u,
            'total' => $this->total,
            'num_comprobante' => $this->num_comprobante,
            'comprobante1' => $this->comprobante ? url($this->comprobante) : null,
            'comprobante2' => $this->comprobante2 ? url($this->comprobante2) : null,
            'observacion' => $this->observacion,
            'id_usuario' => $this->id_usuario,
            'empleado_info' => $this->empleado_info->nombres . ' ' . $this->empleado_info->apellidos,
            'estado' => $this->estado,
            'estado_info' => $this->estado_info->descripcion,
            'estado' => $this->estado,
            'id_lugar' => $this->id_lugar,
            'tiene_factura_info' => $this->sub_detalle_info != null ? $this->sub_detalle_info : true,
            'tiene_factura' => $this->sub_detalle_info != null ? $this->tiene_factura($this->sub_detalle_info) : true,
            'created_at'  => Carbon::parse($this->created_at)
                ->format('d-m-Y H:i'),
            'centro_costo' => $this->tarea_info !== null ? $this->tarea_info?->centroCosto?->nombre:'',
            'subcentro_costo' => $this->empleado_info->grupo==null ?'':$this->empleado_info->grupo?->subCentroCosto?->nombre,
        ];
        return $modelo;
    }
    private function tiene_factura($subdetalle_info)
    {
        $tieneFactura = false;
        foreach ($subdetalle_info as $item) {
            if ($item["tiene_factura"]) {
                $tieneFactura = true;
                break;
            }
        }
        return $tieneFactura;
    }
    private function subdetalle_info($subdetalle_info)
    {
        $descripcion = '';
        $i = 0;
        foreach ($subdetalle_info as $sub_detalle) {
            $descripcion .= $sub_detalle->descripcion;
            $i++;
            if ($i !== count($subdetalle_info)) {
                $descripcion .= ', ';
            }
        }
        return $descripcion;
    }
    private function beneficiario_empleado_info($beneficiarios)
    {
        $descripcion = '';
        $i = 0;
        foreach ($beneficiarios as $beneficiario) {
            $descripcion .= $beneficiario?->empleado_info?->nombres . ' ' . $beneficiario?->empleado_info?->apellidos;
            $i++;
            if ($i !== count($beneficiarios)) {
                $descripcion .= ', ';
            }
        }
        return $descripcion;
    }
    private function cambiar_fecha($fecha)
    {
        $fecha_formateada = Carbon::parse($fecha)->format('Y-m-d');
        return $fecha_formateada;
    }
}
