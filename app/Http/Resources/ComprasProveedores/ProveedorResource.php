<?php

namespace App\Http\Resources\ComprasProveedores;

use App\Models\Proveedor;
use Illuminate\Http\Resources\Json\JsonResource;

class ProveedorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        [$calificacion, $estado] = Proveedor::obtenerCalificacion($this->id);
        // return parent::toArray($request);
        $controller_method = $request->route()->getActionMethod();
        $modelo = [
            'id' => $this->id,
            'empresa' => $this->empresa_id,
            'ruc' => $this->empresa->identificacion,
            'razon_social' => $this->empresa->razon_social,
            'sucursal' => $this->sucursal,
            'ubicacion' => $this->parroquia ? $this->parroquia?->canton->provincia->provincia . ' - ' . $this->parroquia?->canton->canton . ' - ' . $this->parroquia?->parroquia : null,
            'parroquia' => $this->parroquia_id,
            'direccion' => $this->direccion,
            'celular' => $this->celular,
            'telefono' => $this->telefono,
            'estado' => $this->estado,
            'tipos_ofrece' => $this->servicios_ofertados->map(fn($item)=>$item->id),
            'departamentos' => $this->departamentos_califican->map(fn($item)=>$item->id),
            'related_departamentos' => $this->departamentos_califican,
            'calificacion' => $this->calificacion?$this->calificacion:0,
            'estado_calificado' => $this->estado_calificado?$this->estado_calificado: Proveedor::SIN_CONFIGURAR,
            // 'calificacion' => $calificacion,
            // 'estado_calificado' => $estado,
        ];

        if ($controller_method == 'show') {
            //listados
            $modelo['tipos_ofrece'] = $this->servicios_ofertados->map(fn($item)=>$item->id);
            $modelo['categorias_ofrece'] = $this->categorias_ofertadas->map(fn($item)=>$item->id);
            $modelo['departamentos'] = $this->departamentos_califican->map(fn($item)=>$item->id);
            $modelo['contactos'] = ContactoProveedorResource::collection($this->contactos);
        }
        return $modelo;
    }
}
