<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DetalleProductoResource extends JsonResource
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
            'producto' => $this->producto->nombre,
            'descripcion' => $this->descripcion,
            'marca' => $this->modelo->marca->nombre,
            'modelo' => $this->modelo->nombre,
            'serial' => $this->serial,
            'precio_compra' => $this->precio_compra,
            'tipo_fibra'=>$this->tipo_fibra==null?null: $this->tipo_fibra->nombre,
            'categoria' => $this->producto->categoria->nombre,
            'hilos'=>$this->hilo==null?null: $this->hilo->nombre,
            'punta_a' => $this->punta_a,
            'punta_b' => $this->punta_b,
            'punta_corte' => $this->punta_corte,
            //variables auxiliares
            'tiene_serial'=>is_null($this->serial)?false:true,
            'es_fibra'=>$this->comprobarFibra($this->id),
            'tiene_precio_compra'=>$this->precio_compra>0?true:false
        ];
        if ($controller_method == 'show') {
            $modelo['producto'] = $this->producto_id;
            $modelo['modelo'] = $this->modelo_id;
            $modelo['tipo_fibra'] = $this->tipo_fibra_id;
            $modelo['hilos'] = $this->hilo_id;
        }
        return $modelo;
    }
}
