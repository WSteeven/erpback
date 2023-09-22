<?php

namespace App\Http\Resources\ComprasProveedores;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoriaOfertaProveedorResource extends JsonResource
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
            'tipo_oferta' => $this->oferta->nombre,
            'tipo_oferta_id' => $this->tipo_oferta_id,
            'estado' => $this->estado,
            'departamentos' => $this->departamentos_responsables->map(fn ($item) => $item->id),
            'nombres_departamentos' => $this->departamentos_responsables->map(fn ($item) => $item->nombre),

        ];

        if ($controller_method == 'show') {
            $modelo['tipo_oferta'] = $this->tipo_oferta_id;
        }

        return $modelo;
    }
}
