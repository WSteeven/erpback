<?php

namespace App\Http\Resources\ComprasProveedores;

use Illuminate\Http\Resources\Json\JsonResource;

class BeneficiarioResource extends JsonResource
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
            'id' => $this['id'],
            'codigo_beneficiario' => $this['codigo_beneficiario'],
            'tipo_documento' => $this['tipo_documento'],
            'identificacion_beneficiario' => $this['identificacion_beneficiario'],
            'nombre_beneficiario' => $this['nombre_beneficiario'],
            'direccion' => $this['direccion'],
            'telefono' => $this['telefono'],
            'localidad' => $this['localidad'],
            'canton' => $this['canton_id'],
        ];
    }
}
