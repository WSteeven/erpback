<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CausaIntervencionResource extends JsonResource
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
            'activo' => $this->activo,
            'tipo_trabajo' => $this->tipoTrabajo->descripcion,
            'tipo_trabajo_id' => $this->tipo_trabajo_id,
            'cliente' => $this->tipoTrabajo->cliente->empresa->razon_social,
        ];

        if ($controller_method == 'show') {
            $modelo['cliente'] = $this->tipoTrabajo->cliente_id;
            $modelo['tipo_trabajo'] = $this->tipo_trabajo_id;
        }

        return $modelo;
    }
}
