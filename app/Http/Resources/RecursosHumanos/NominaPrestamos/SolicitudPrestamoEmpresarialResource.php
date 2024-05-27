<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class SolicitudPrestamoEmpresarialResource extends JsonResource
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
            'id' => $this->id,
            'solicitante' => $this->solicitante,
            'solicitante_info' => $this->empleado_info!=null?$this->empleado_info->nombres . ' ' . $this->empleado_info->apellidos:'',
            'fecha' =>  $controller_method === 'show'?$this->fecha:$this->cambiar_fecha($this->fecha),
            'monto' =>  $this->monto,
            'plazo' => $this->plazo,
            'motivo' =>  $this->motivo,
            'observacion' => $this->observacion,
            'cargo_utilidad' =>$this->periodo_id != null ? true:false,
            'periodo' =>   $this->periodo_id,
            'periodo_info' => $this->periodo_info? $this->periodo_info->nombre:'' ,
            'valor_utilidad' => $this->valor_utilidad,
            'foto' =>  $this->foto?url($this->foto):null,
            'estado' => $this->estado,
            'estado_info' =>  $this->estado_info!=null ? $this->estado_info->nombre:'',
        ];
        return $modelo;
    }
    private function cambiar_fecha($fecha)
    {
        $fecha_formateada = Carbon::parse($fecha)->format('d-m-Y');
        return $fecha_formateada;
    }

}
