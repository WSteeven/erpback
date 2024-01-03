<?php

namespace App\Http\Resources\Ventas;

use Illuminate\Http\Resources\Json\JsonResource;

class EscenarioVentaJPResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $modelo = [
            'id'=> $this->id,
            'mes'=>$this->mes,
            'numero_mes'=>$this->numero_mes,
            'apoyo_das_fijos'=>$this->apoyo_das_fijos,
            'vendedores'=>$this->vendedores,
            'productividad_minima'=>$this->productividad_minima,
            'vendedores_acumulados'=>$this->vendedores_acumulados,
            'total_ventas_adicionales'=>$this->total_ventas_adicionales,
            'arpu_prom'=>$this->arpu_prom,
            'altas'=>$this->altas,
            'bajas'=>$this->bajas,
            'neta'=>$this->neta,
            'stock'=>$this->stock,
            'stock_que_factura'=>$this->stock_que_factura,
        ];
        return $modelo;
    }
}
