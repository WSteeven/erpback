<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TipoTareaResource extends JsonResource
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
            'nombre' => $this->nombre,
            'cliente' => $this->cliente->empresa->razon_social,
        ];

        if ($controller_method == 'show') {
            $modelo['cliente'] = $this->cliente_id;
        }

        return $modelo;
    }
}
