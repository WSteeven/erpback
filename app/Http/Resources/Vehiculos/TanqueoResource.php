<?php

namespace App\Http\Resources\Vehiculos;

use Illuminate\Http\Resources\Json\JsonResource;

class TanqueoResource extends JsonResource
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
            'vehiculo' => $this->vehiculo->placa,
            'solicitante' => $this->solicitante->nombres . ' ' . $this->solicitante->apellidos,
            'fecha_hora' => $this->fecha_hora,
            'km_tanqueo' => $this->km_tanqueo,
            'monto' => $this->monto,
        ];

        if ($controller_method == 'show' || $controller_method == 'ultima') {
            $modelo['vehiculo'] = $this->vehiculo_id;
            $modelo['solicitante_id'] = $this->solicitante_id;
            $modelo['imagen_comprobante'] = $this->imagen_comprobante ? url($this->imagen_comprobante) : null;
            $modelo['imagen_tablero'] = $this->imagen_tablero ? url($this->imagen_tablero) : null;
        }
        return $modelo;
    }
}
