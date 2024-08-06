<?php

namespace App\Http\Resources\Externos;

use App\Models\RecursosHumanos\SeleccionContratacion\Postulante;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserExternalInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->persona != null ? $this->persona->id : 0,
            'usuario' => $this->persona != null ? Postulante::extraerNombresApellidos($this->persona) : '',
            'nombres' => $this->persona != null ? $this->persona->nombres : '',
            'apellidos' => $this->persona != null ? $this->persona->apellidos : '',
            'email' => $this->email,
            'identificacion' =>  $this->persona != null ? $this->persona->identificacion : '',
            'telefono' =>  $this->persona != null ? $this->persona->telefono : '',
            'fecha_ingreso' =>  $this->persona != null ? $this->persona->fecha_ingreso : '',
            'fecha_nacimiento' =>  $this->persona != null ? $this->persona->fecha_nacimiento : '',
            'jefe_id' => $this->persona != null ? $this->persona->jefe_id : 0,
            'usuario_id' => $this->id,
            'sucursal_id' =>  $this->persona != null ? $this->persona->sucursal_id : '',
            'grupo_id' => $this->persona != null ? $this->persona->grupo_id : 0,
            'grupo' => $this->persona != null ? $this->persona->grupo?->nombre : '',
            'roles' => $this->getRoleNames(), // ->toArray()),
            'estado' => $this->persona != null ? $this->persona->estado : false,
            'cargo' => $this->persona != null ? $this->persona->cargo?->nombre : '',
            'departamento' => $this->persona ? $this->persona->departamento_id : null,
            'foto_url' => $this->foto_url ? url($this->foto_url) : url('/storage/sinfoto.png'),
            'nombre_canton' => $this->persona->canton?->canton??null,
            'tipo_sangre' => $this->persona?->tipo_sangre,
            'area_info' =>  $this->persona?->area?->nombre,
            'nombre_cargo' => $this->persona?->cargo?->nombre,
        ];
    }
}
