<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClienteResource extends JsonResource
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
            'empresa' => $this->empresa_id,
            'razon_social' => $this->empresa->razon_social,
            'canton' => $this->parroquia?->canton->canton,
            'parroquia' => $this->parroquia?->parroquia,
            'requiere_bodega' => $this->requiere_bodega,
            'estado' => $this->estado,
            'logo_url' => $this->logo_url? url($this->logo_url):null,
        ];

        if ($controller_method == 'show') {
            $modelo['empresa'] = $this->empresa_id;
            $modelo['canton'] = $this->parroquia->canton_id;
            $modelo['parroquia'] = $this->parroquia_id;
        }

        return $modelo;
    }
}
