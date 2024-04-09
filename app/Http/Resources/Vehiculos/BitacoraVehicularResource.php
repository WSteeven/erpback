<?php

namespace App\Http\Resources\Vehiculos;

use Illuminate\Http\Resources\Json\JsonResource;

class BitacoraVehicularResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // Log::channel('testing')->info('Log', ['Resource de bitacora...', $this]);
        $controller_method = $request->route()->getActionMethod();
        $modelo = [
            'id' => $this->id,
            'fecha' => date('Y-m-d', strtotime($this->fecha)),
            'hora_salida' => $this->hora_salida,
            'hora_llegada' => $this->hora_llegada,
            'km_inicial' => $this->km_inicial,
            'km_final' => $this->km_final,
            'tanque_inicio' => $this->tanque_inicio,
            'tanque_final' => $this->tanque_final,
            'firmada' => $this->firmada,
            'chofer' => $this->chofer->nombres.' '.$this->chofer->apellidos,
            'chofer_id' => $this->chofer_id,
            'vehiculo' => $this->vehiculo->placa,
        ];

        if ($controller_method == 'show') {
            // $modelo['chofer'] = $this->chofer_id;
            // $modelo['vehiculo'] = $this->vehiculo_id;
            $modelo['actividadesRealizadas'] = [];
        }

        return $modelo;
    }
}
