<?php

namespace App\Http\Resources;

use App\Models\Empleado;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartamentoResource extends JsonResource
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
            'responsable' => $this->responsable ? Empleado::extraerNombresApellidos($this->responsable) : null,
        ];

        if ($controller_method == 'show') {
            $modelo['responsable'] = $this->responsable_id;
        }

        return $modelo;
    }
}
