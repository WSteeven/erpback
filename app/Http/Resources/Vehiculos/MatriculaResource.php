<?php

namespace App\Http\Resources\Vehiculos;

use Illuminate\Http\Resources\Json\JsonResource;

class MatriculaResource extends JsonResource
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
            'vehiculo' => $this->vehiculo?->placa,
            'fecha_matricula' => date('m-Y', strtotime($this->fecha_matricula)),
            'proxima_matricula' => date('m-Y', strtotime($this->proxima_matricula)),
            'matriculador' => $this->matriculador,
            'matriculado' => $this->matriculado,
            'estado' => $this->matriculado,
            'observacion' => $this->observacion,
            'monto' => $this->monto,
        ];

        if ($controller_method == 'show') {
            $modelo['vehiculo'] = $this->vehiculo_id;
        }

        return $modelo;
    }
}
