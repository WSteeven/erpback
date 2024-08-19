<?php

namespace App\Http\Resources\RecursosHumanos\SeleccionContratacion;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class UserExternalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        Log::channel('testing')->info('Log', ['UserExternalResource', $this->persona]);

        return [
            'id' => $this->id,
            'nombres' => $this->persona  ? $this->persona->nombres : '',
            'apellidos' => $this->persona  ? $this->persona->apellidos : '',
            'email' => $this->email,
            'tipo_documento_identificacion' => $this->persona ? $this->persona->tipo_documento_identificacion : '',
            'numero_documento_identificacion' => $this->persona ? $this->persona->numero_documento_identificacion : '',
            'telefono' => $this->persona  ? $this->persona->telefono : '',
            'genero' => $this->persona ? $this->persona->genero : null,
            'pais' => $this->persona ? $this->persona->pais_id : null,
            'fecha_nacimiento' => $this->persona->fecha_nacimiento ?? null,
            'identidad_genero' => $this->persona->identidad_genero_id ?? null,
            'direccion' => $this->persona->direccion ?? null,
        ];
    }
}
