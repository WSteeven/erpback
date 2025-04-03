<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CuotaDescuentoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'descuento_id'=>$this->descuento_id,
            'num_cuota'=>$this->num_cuota,
            'mes_vencimiento'=>$this->mes_vencimiento,
            'valor_cuota'=>$this->valor_cuota,
            'pagada'=>$this->pagada,
            'comentario'=>$this->comentario,
        ];
    }
}
