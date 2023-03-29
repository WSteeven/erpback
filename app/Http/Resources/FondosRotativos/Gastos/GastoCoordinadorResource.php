<?php

namespace App\Http\Resources\FondosRotativos\Gastos;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class GastoCoordinadorResource extends JsonResource
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
            'fecha_gasto' => $this->cambiar_fecha($this->fecha_gasto),
            'lugar' => $this->id_lugar,
            'grupo' => $this->id_grupo,
            'grupo_info' => $this->grupo_info->nombre,
            'motivo_info' => $this->detalle_motivo_info != null ? $this->motivo_info($this->detalle_motivo_info):'',
            'motivo' => $this->detalle_motivo_info != null ? $this->detalle_motivo_info->pluck('id'):null,
            'lugar_info' => $this->lugar_info->canton,
            'monto' => $this->monto,
            'observacion' => $this->observacion,
            'usuario' => $this->id_usuario,
            'usuario_info' => $this->usuario_info->name,
        ];
        return $modelo;
    }
    private function motivo_info($motivo_info){
        $descripcion = '';
        $i=0;
        foreach($motivo_info as $motivo){
            $descripcion .= $motivo->nombre;
            $i++;
            if($i < count($motivo_info)){
                $descripcion .= ', ';
            }
        }
        return $descripcion;
    }



   private function cambiar_fecha($fecha){
    $fecha_formateada = Carbon::parse( $fecha)->format('d-m-Y');
        return $fecha_formateada;
    }
}
