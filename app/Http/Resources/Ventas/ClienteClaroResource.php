<?php

namespace App\Http\Resources\Ventas;

use Illuminate\Http\Resources\Json\JsonResource;

class ClienteClaroResource extends JsonResource
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
            'supervisor' => $this->supervisor?->nombres . ' ' . $this->supervisor?->apellidos,
            'identificacion' => $this->identificacion,
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'cliente_info' => $this->nombres . ' ' . $this->apellidos,
            'direccion' => $this->direccion,
            'telefono1' => $this->telefono1,
            'telefono2' => $this->telefono2,
            'canton' => $this->canton?->nombre,
            'parroquia' => $this->parroquia?->parroquia,
            'tipo_cliente' => $this->tipo_cliente,
            'correo_electronico' => $this->correo_electronico,
            'foto_cedula_frontal' => $this->foto_cedula_frontal,
            'foto_cedula_posterior' => $this->foto_cedula_posterior,
            'fecha_expedicion_cedula' => $this->fecha_expedicion_cedula,
            'activo' => $this->activo,
        ];

        if ($controller_method == 'show') {
            $modelo['supervisor'] = $this->supervisor_id;
        }
        return $modelo;
    }
}
