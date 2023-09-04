<?php

namespace App\Http\Resources;

use App\Http\Resources\ComprasProveedores\ContactoProveedorResource;
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
            // 'celular' => $this->celular,
            // 'telefono' => $this->telefono,
            'correo' => $this->correo,
            'pais' => $this->canton?->provincia->pais->pais,
            'provincia' => $this->canton?->provincia->provincia,
            'canton' => $this->canton?->canton,
            // 'ciudad' => $this->ciudad,
            'direccion' => $this->direccion,
            'agente_retencion' => $this->agente_retencion,
            'regimen_tributario' => $this->regimen_tributario,
            'sitio_web' => $this->sitio_web,
            'lleva_contabilidad' => $this->lleva_contabilidad,
            'contribuyente_especial' => $this->contribuyente_especial,
            'actividad_economica' => $this->actividad_economica,
        ];

        if ($controller_method == 'show') {
            $modelo['pais'] = $this->canton?->provincia->pais_id;
            $modelo['provincia'] = $this->canton?->provincia_id;
            $modelo['canton'] = $this->canton_id;
            $modelo['contactos'] = ContactoProveedorResource::collection($this->contactos);
            $modelo['sucursal'] = count($this->proveedores)>0 ? 'Sucursal ' . count($this->proveedores) : 'matriz';
        }
        return $modelo;
    }
}
