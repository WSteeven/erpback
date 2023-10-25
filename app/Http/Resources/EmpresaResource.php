<?php

namespace App\Http\Resources;

use App\Http\Resources\ComprasProveedores\ContactoProveedorResource;
use App\Http\Resources\ComprasProveedores\DatoBancarioProveedorResource;
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
            'nombre_provincia' => $this->canton?->provincia->provincia,
            'canton' => $this->canton?->canton,
            // 'ciudad' => $this->ciudad,
            'direccion' => $this->direccion,
            'agente_retencion' => $this->agente_retencion,
            'regimen_tributario' => $this->regimen_tributario,
            'sitio_web' => $this->sitio_web,
            'lleva_contabilidad' => $this->lleva_contabilidad,
            'contribuyente_especial' => $this->contribuyente_especial,
            'actividad_economica' => $this->actividad_economica,
            'created_at' => date('d/m/Y H:i:s', strtotime($this->created_at)),
            'updated_at' => date('d/m/Y H:i:s', strtotime($this->updated_at)),
            'representante_legal' => $this->representante_legal,
            'identificacion_representante' => $this->identificacion_representante,
            'antiguedad_proveedor' => $this->antiguedad_proveedor,
            'es_cliente' => $this->es_cliente,
            'es_proveedor' => $this->es_proveedor,
        ];

        if ($controller_method == 'show') {
            $modelo['pais'] = $this->canton?->provincia->pais_id;
            $modelo['provincia'] = $this->canton?->provincia_id;
            $modelo['canton'] = $this->canton_id;
            $modelo['contactos'] = ContactoProveedorResource::collection($this->contactos);
            $modelo['datos_bancarios'] = DatoBancarioProveedorResource::collection($this->datos_bancarios);
            $modelo['sucursal'] = count($this->proveedores) > 0 ? 'Sucursal ' . count($this->proveedores) : 'matriz';
        }
        return $modelo;
    }
}
