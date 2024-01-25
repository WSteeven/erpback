<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConfiguracionGeneralResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'logo_claro' => $this->logo_claro ? url($this->logo_claro) : null,
            'logo_oscuro' => $this->logo_oscuro ? url($this->logo_oscuro) : null,
            'logo_marca_agua' => $this->logo_marca_agua ? url($this->logo_marca_agua) : null,
            'ruc' => $this->ruc,
            'representante' => $this->representante,
            'razon_social' => $this->razon_social,
            'nombre_comercial' => $this->nombre_comercial,
            'direccion_principal' => $this->direccion_principal,
            'telefono' => $this->telefono,
            'moneda' => $this->moneda,
            'tipo_contribuyente' => $this->tipo_contribuyente,
            'celular1' => $this->celular1,
            'celular2' => $this->celular2,
            'correo_principal' => $this->correo_principal,
            'correo_secundario' => $this->correo_secundario,
            'sitio_web' => $this->sitio_web,
            'direccion_secundaria1' => $this->direccion_secundaria1,
            'direccion_secundaria2' => $this->direccion_secundaria2,
            'nombre_empresa' => $this->nombre_empresa,
        ];
    }
}
