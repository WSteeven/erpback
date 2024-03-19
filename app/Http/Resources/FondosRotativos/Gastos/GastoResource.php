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
            'lugar_info' => $this->canton->canton,
            'num_tarea' => $this->id_tarea == null ? 0 : $this->id_tarea,
            'subTarea' => $this->id_subtarea == null ? 0 : $this->id_subtarea,
            'subTarea_info' => $this->subTarea != null ? $this->subTarea->codigo_subtarea . ' - ' . $this->subTarea->titulo : 'Sin Subtarea',
            'tarea_info' =>  $this->tarea != null ? $this->tarea->codigo_tarea . ' - ' . $this->tarea->detalle : 'Sin Tarea',
            'tarea_cliente' =>  $this->tarea != null ? $this->tarea->codigo_tarea_cliente : 'Sin Tarea',
            'proyecto' => $this->id_proyecto != null ? $this->id_proyecto : 0,
            'proyecto_info' => $this->Proyecto != null ? $this->Proyecto->codigo_proyecto . ' - ' . $this->Proyecto->nombre : 'Sin Proyecto',
            'ruc' => $this->ruc,
            'factura' => strlen($this->factura)>1?$this->factura:null,
            'aut_especial_user' => $this->authEspecialUser->nombres . ' ' . $this->authEspecialUser->apellidos,
            'aut_especial' => $this->aut_especial,
            'detalle_info' => $this->detalle_info->descripcion,
            'detalle_estado' => $this->detalle_estado,
            'sub_detalle_info' => $this->subDetalle != null ? $this->subdetalle_info($this->subDetalle) : '',
            'beneficiarios' => $this->beneficiarioGasto != null ? $this->beneficiarioGasto->pluck('empleado_id') : null,
            'beneficiarios_info' => $this->beneficiario_empleado_info($this->beneficiarioGasto),
            'sub_detalle' => $this->subDetalle != null ? $this->subDetalle->pluck('id') : null,
            'vehiculo' => $this->gastoVehiculo != null ? $this->gastoVehiculo->id_vehiculo : '',
            'placa' =>  $this->gastoVehiculo != null ? $this->gastoVehiculo->placa : '',
            'es_vehiculo_alquilado' =>  $this->gastoVehiculo != null ? $this->gastoVehiculo->es_vehiculo_alquilado : null,
            'kilometraje' => $this->gastoVehiculo != null ? $this->gastoVehiculo->kilometraje : '',
            'detalle' => $this->detalle,
            'cantidad' => $this->cantidad,
            'valor_u' => $this->valor_u,
            'total' => $this->total,
            'num_comprobante' => $this->num_comprobante,
            'comprobante1' => $this->comprobante ? url($this->comprobante) : null,
            'comprobante2' => $this->comprobante2 ? url($this->comprobante2) : null,
            'observacion' => $this->observacion,
            'id_usuario' => $this->id_usuario,
            'empleado_info' => $this?->empleado?->nombres . ' ' . $this?->empleado?->apellidos,
            'estado' => $this->estado,
            'estado_info' => $this?->estadoViatico?->descripcion,
            'estado' => $this->estado,
            'id_lugar' => $this->id_lugar,
            'tiene_factura_info' => $this->subDetalle != null ? $this->subDetalle : true,
            'tiene_factura' => $this->subDetalle != null ? $this->tiene_factura($this->subDetalle) : true,
            'created_at'  => Carbon::parse($this->created_at)
                ->format('d-m-Y H:i'),
            'centro_costo' => $this->tarea !== null ? $this->tarea?->centroCosto?->nombre:'',
            'subcentro_costo' => $this?->empleado?->grupo==null ?'':$this?->empleado?->grupo?->subCentroCosto?->nombre,
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
            $descripcion .= $beneficiario?->empleado?->nombres . ' ' . $beneficiario?->empleado?->apellidos;
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
