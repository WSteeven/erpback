<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CodigoClienteResource extends JsonResource
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
            'codigo' => $this->codigo,
            'cliente' => $this->cliente->empresa->razon_social,
            'producto' => $this->detalle->descripcion,
            'nombre_cliente'=>$this->nombre_cliente,
        ];

        if ($controller_method == 'show') {
            $modelo['cliente'] = $this->cliente_id;
            $modelo['producto'] = $this->producto_id;
        }
        
        return $modelo;
    }
}
