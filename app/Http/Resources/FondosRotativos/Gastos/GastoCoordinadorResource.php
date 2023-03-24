<?php

namespace App\Http\Resources\FondosRotativos\Gastos;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'motivo' => $this->id_motivo,
            'motivo_info' => $this->motivo_info->nombre,
            'lugar_info' => $this->lugar_info->canton,
            'monto' => $this->monto,
            'observacion' => $this->observacion,
            'usuario' => $this->id_usuario,
            'usuario_info' => $this->usuario_info->name,
        ];
        return $modelo;
    }

   private function cambiar_fecha($fecha){
    $fecha_formateada = Carbon::parse( $fecha)->format('d-m-Y');
        return $fecha_formateada;
    }
}
