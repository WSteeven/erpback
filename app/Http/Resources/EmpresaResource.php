<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmpresaResource extends JsonResource
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
            "id" => $this->id,
            'identificacion' => $this->identificacion,
            'tipo_contribuyente' => $this->tipo_contribuyente,
            'razon_social' => $this->razon_social,
            'nombre_comercial' => $this->nombre_comercial,
            'celular' => $this->celular,
            'telefono' => $this->telefono,
            'correo' => $this->correo,
            'pais' => $this->canton?->provincia->pais->pais,
            'provincia' => $this->canton?->provincia->provincia,
            'canton' => $this->canton?->canton,
            'ciudad' => $this->ciudad,
            'direccion' => $this->direccion,
            'agente_retencion' => $this->agente_retencion,
            'tipo_negocio' => $this->tipo_negocio,
        ];

        if ($controller_method == 'show') {
            $modelo['pais'] = $this->canton?->provincia->pais_id;
            $modelo['provincia'] = $this->canton?->provincia_id;
            $modelo['canton'] = $this->canton_id;
        }
        return $modelo;
    }
}
