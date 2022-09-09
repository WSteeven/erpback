<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DetallesProductoResource extends JsonResource
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

        $modelo =  [
            'id' => $this->id,
            'codigo_barras' => $this->codigo_barras,
            'nombre_id' => $this->nombre_id,
            'descripcion' => $this->descripcion,
            'modelo_id' => $this->modelo_id,
            'precio' => $this->precio,
            'serial' => $this->serial,
            'categoria_id' => $this->categoria_id,
            'tipo_fibra_id'=>$this->tipo_fibra_id,
            'hilo_id'=>$this->hilo_id,
            'punta_a' => $this->punta_a,
            'punta_b' => $this->punta_b,
            'punta_corte' => $this->punta_corte,
            'condicion_id' => $this->condicion_id,
            //'estado' => $this->estado
        ];
        if ($controller_method == 'show') {
            $modelo['cliente'] = $this->cliente_id;
        }
        return $modelo;
    }
}
