<?php

namespace App\Http\Resources\RecursosHumanos;

use Illuminate\Http\Resources\Json\JsonResource;

class EmpleadoLiteResource extends JsonResource{

    public function toArray($request)
    {
        return  [
            'id' => $this->id,
            'identificacion' => $this->identificacion,
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'email' => $this->user ? $this->user->email : '',
            'jefe' => $this->jefe ? $this->jefe->nombres . ' ' . $this->jefe->apellidos : 'N/A',
            'canton' => $this->canton ? $this->canton->canton : 'NO TIENE',
            'grupo' => $this->grupo?->nombre,
            'cargo' => $this->cargo?->nombre,
            'estado' => $this->estado, //?Empleado::ACTIVO:Empleado::INACTIVO,
        ];
    }
}
