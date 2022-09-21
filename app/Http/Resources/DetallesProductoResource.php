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
            'producto' => $this->producto->nombre,
            'descripcion' => $this->descripcion,
            'marca' => $this->modelo->marca->nombre,
            'modelo' => $this->modelo->nombre,
            'precio_compra' => $this->precio_compra,
            'serial' => $this->serial,
            'categoria' => $this->producto->categoria->nombre,
            'tipo_fibra'=>$this->tipo_fibra==null?null: $this->tipo_fibra->nombre,
            'hilo'=>$this->hilo==null?null: $this->hilo->nombre,
            'punta_a' => $this->punta_a,
            'punta_b' => $this->punta_b,
            'punta_corte' => $this->punta_corte,
            //'estado' => $this->estado
        ];
        if ($controller_method == 'show') {
            $modelo['producto'] = $this->producto_id;
            $modelo['modelo'] = $this->modelo_id;
            $modelo['tipo_fibra'] = $this->tipo_fibra_id;
        }
        return $modelo;
    }
}
