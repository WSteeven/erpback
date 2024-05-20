<?php

namespace App\Http\Resources\RecursosHumanos\SeleccionContratacion;

use Illuminate\Http\Resources\Json\JsonResource;

class UserExternalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $postulante = $this->postulante;
        return [
            'nombres' => $this->postulante  ? $postulante->nombres : '',
            'apellidos' => $this->postulante  ? $postulante->apellidos : '',
            'tipo_documento_identificacion' => $this->postulante ? $postulante->tipo_documento_identificacion : '',
            'numero_documento_identificacion' => $this->postulante ? $postulante->numero_documento_identificacion : '',
            'telefono' => $this->postulante  ? $postulante->telefono : '',
        ];
    }
}
