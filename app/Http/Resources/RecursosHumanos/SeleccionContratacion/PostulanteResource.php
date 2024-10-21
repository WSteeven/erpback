<?php

namespace App\Http\Resources\RecursosHumanos\SeleccionContratacion;

use Illuminate\Http\Resources\Json\JsonResource;

class PostulanteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $modelo = [ 'id' => $this->id,
        'numero_documento_identificacion' => $this->numero_documento_identificacion,
        'nombres' => $this->nombres,
        'apellidos' => $this->apellidos,
        'telefono' => $this->telefono,
        'email' => $this->usuario ? $this->usuario->email : '',
        'usuario' => $this->usuario?->name,
    ];
        return $modelo;
    }
}
