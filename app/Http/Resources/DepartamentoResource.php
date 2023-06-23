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
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'activo' => $this->activo,
<<<<<<< HEAD
            'responsable' => $this->responsable->nombres . ' ' . $this->responsable->apellidos,
=======
            'responsable' => $this->responsable ? Empleado::extraerNombresApellidos($this->responsable) : null,
>>>>>>> 4605126f578efcaa448cf27731509a11f9fc2bda
        ];
    }
}
