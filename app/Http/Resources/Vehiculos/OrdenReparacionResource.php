<?php

namespace App\Http\Resources\Vehiculos;

use Illuminate\Http\Resources\Json\JsonResource;
use Src\Shared\Utils;

class OrdenReparacionResource extends JsonResource
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
            'solicitante' => $this->solicitante->nombres . ' ' . $this->solicitante->apellidos,
            'vehiculo' => $this->vehiculo->placa,
            'autorizacion' => $this->autorizacion->nombre,
            'servicios',
            'fecha' => date(Utils::MASKFECHA, strtotime($this->created_at)),
        ];

        if ($controller_method == 'show') {
            $modelo['servicios'] = $this->servicios ? Utils::convertirStringComasArray($this->servicios) : null;
            $modelo['solicitante'] = $this->solicitante_id;
            $modelo['autorizador'] = $this->autorizador_id;
            $modelo['autorizacion'] = $this->autorizacion_id;
            $modelo['vehiculo'] = $this->vehiculo_id;
        }

        return $modelo;
    }
}
