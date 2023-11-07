<?php

namespace App\Http\Resources\Ventas;

use Illuminate\Http\Resources\Json\JsonResource;

class BonoMensualCumplimientoResource extends JsonResource
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
            'vendedor_id' => $this->vendedor_id,
            'vendedor' => $this->vendedor,
            'vendedor_info' => $this->vendedor->empleado->nombres.' '.$this->vendedor->empleado->apellidos,
            'bono_id' => $this->bono_id,
            'bono' => $this->bono_id,
            'bono_info' => $this->bono!=null? $this->bono->valor:' ',
            'cant_ventas' => $this->cant_ventas,
            'mes' => $this->mes,
            'valor' =>  number_format($this->valor,2)
        ];
        return $modelo;
    }
}
