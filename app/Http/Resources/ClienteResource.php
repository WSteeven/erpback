<?php

namespace App\Http\Resources;

class ClienteResource extends BaseResource
{
    protected function construirModelo()
    {
        $modelo = [
            'id' => $this->id,
            // 'empresa_id' => $this->empresa?->razon_social, //es una copia de razon_social ya que en el front se debe depurar el nombre de variable para no tener errores
            'empresa' => $this->empresa_id,
            'ruc' => $this->empresa?->identificacion,
            'razon_social' => $this->empresa?->razon_social,
            'canton' => $this->parroquia?->canton?->canton,
            'parroquia' => $this->parroquia?->parroquia,
            'requiere_bodega' => $this->requiere_bodega,
            'requiere_fr' => $this->requiere_fr,
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
