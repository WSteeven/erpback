<?php

namespace App\Http\Resources\Vehiculos;

use Illuminate\Http\Resources\Json\JsonResource;

class VehiculoResource extends JsonResource
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
            'placa' => $this->placa,
            'num_chasis' => $this->num_chasis,
            'num_motor' => $this->num_motor,
            'anio_fabricacion' => $this->anio_fabricacion,
            'cilindraje' => $this->cilindraje,
            'rendimiento' => $this->rendimiento,
            'marca' => $this->modelo->marca->nombre,
            'modelo' => $this->modelo->nombre,
            'combustible' => $this->combustible?->nombre,
            'traccion' => $this->traccion,
            'tipo_vehiculo' => $this->tipoVehiculo?->nombre,
            'aire_acondicionado' => $this->aire_acondicionado,
            'capacidad_tanque' => $this->capacidad_tanque,
            'color' => $this->color,
        ];

        if ($controller_method == 'show') {
            $modelo['marca'] = $this->modelo->marca->id;
            $modelo['modelo'] = $this->modelo_id;
            $modelo['combustible'] = $this->combustible_id;
            $modelo['tipo_vehiculo'] = $this->tipo_vehiculo_id;
            $modelo['tiene_gravamen'] = $this->tiene_gravamen;
            $modelo['prendador'] = $this->prendador;
            $modelo['tipo'] = $this->tipo;
            $modelo['tiene_rastreo'] = $this->tiene_rastreo;
            $modelo['propietario'] = $this->propietario;
        }

        return $modelo;
    }
}
