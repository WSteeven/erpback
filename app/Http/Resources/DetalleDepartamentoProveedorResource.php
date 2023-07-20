<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DetalleDepartamentoProveedorResource extends JsonResource
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
            'id'=>$this->id,
            'departamento'=>$this->departamento->nombre,
            'razon_social'=>$this->proveedor->empresa->razon_social,
            'sucursal'=>$this->proveedor->sucursal,
            'empleado'=>$this->empleado ? $this->empleado->nombres . ' ' . $this->empleado->apellidos : 'N/A',
            'calificacion'=>$this->calificacion,
            'fecha_calificacion'=>$this->fecha_calificacion,
            'created_at'=>date('d/m/Y', strtotime($this->created_at)),
        ];
    }
}
