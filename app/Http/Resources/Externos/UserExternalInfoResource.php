<?php

namespace App\Http\Resources\Externos;

use App\Models\RecursosHumanos\SeleccionContratacion\Postulante;
use Illuminate\Http\Resources\Json\JsonResource;

class UserExternalInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $persona = $this->persona;
        return [
            'id' => $this->persona != null ? $this->persona->id : 0,
            'usuario' => $this->persona != null ? Postulante::extraerNombresApellidos($persona) : '',
            'nombres' => $this->persona != null ? $persona->nombres : '',
            'apellidos' => $this->persona != null ? $persona->apellidos : '',
            'email' => $this->email,
            'identificacion' =>  $this->persona != null ? $persona->identificacion : '',
            'telefono' =>  $this->persona != null ? $persona->telefono : '',
            'fecha_ingreso' =>  $this->persona != null ? $persona->fecha_ingreso : '',
            'fecha_nacimiento' =>  $this->persona != null ? $persona->fecha_nacimiento : '',
            'jefe_id' => $this->persona != null ? $persona->jefe_id : 0,
            'usuario_id' => $this->id,
            'sucursal_id' =>  $this->persona != null ? $persona->sucursal_id : '',
            'grupo_id' => $this->persona != null ? $persona->grupo_id : 0,
            'grupo' => $this->persona != null ? $persona->grupo?->nombre : '',
            'roles' => $this->getRoleNames(), // ->toArray()),
            'estado' => $this->persona != null ? $persona->estado : false,
            'cargo' => $this->persona != null ? $persona->cargo?->nombre : '',
            'departamento' => $this->persona ? $persona->departamento_id : null,
            'foto_url' => $this->foto_url ? url($this->foto_url) : url('/storage/sinfoto.png'),
            'nombre_canton' => $persona->canton?->canton,
            'tipo_sangre' => $persona->tipo_sangre,
            'area_info' =>  $persona->area?->nombre,
            'nombre_cargo' => $persona->cargo?->nombre,
        ];
    }
}
