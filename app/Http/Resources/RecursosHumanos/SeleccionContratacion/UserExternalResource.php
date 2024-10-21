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
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
//        Log::channel('testing')->info('Log', ['UserExternalResource', $this->persona]);

        return [
            'id' => $this->id,
            'nombres' => $this->persona?->nombres,
            'apellidos' => $this->persona?->apellidos,
            'email' => $this->email,
            'tipo_documento_identificacion' => $this->persona?->tipo_documento_identificacion,
            'numero_documento_identificacion' => $this->persona?->numero_documento_identificacion,
            'telefono' => $this->persona?->telefono,
            'correo_personal' => $this->persona->correo_personal,
            'direccion' => $this->persona?->direccion,
            'fecha_nacimiento' => $this->persona?->fecha_nacimiento,
            'genero' => $this->persona?->genero,
            'identidad_genero' => $this->persona?->identidad_genero_id,
            'pais' => $this->persona?->pais_id,
        ];
    }
}
