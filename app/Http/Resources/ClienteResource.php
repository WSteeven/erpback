<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClienteResource extends BaseResource
{
    protected function construirModelo($request)
    {
        $modelo = [
            'id' => $this->id,
            'empresa' => $this->empresa_id,
            'ruc' => $this->empresa?->identificacion,
            'razon_social' => $this->empresa?->razon_social,
            'canton' => $this->parroquia?->canton->canton,
            'parroquia' => $this->parroquia?->parroquia,
            'requiere_bodega' => $this->requiere_bodega,
            'estado' => $this->estado,
            'logo_url' => $this->logo_url ? url($this->logo_url) : null,
        ];

        if ($this->controllerMethodIsShow()) {
            $modelo['empresa'] = $this->empresa_id;
            $modelo['canton'] = $this->parroquia?->canton_id;
            $modelo['parroquia'] = $this->parroquia_id;
        }

        return $modelo;
    }
}
