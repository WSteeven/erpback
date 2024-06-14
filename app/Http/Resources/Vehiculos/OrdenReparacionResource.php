<?php

namespace App\Http\Resources\Vehiculos;

use App\Models\Vehiculos\Servicio;
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
            'observacion' => $this->observacion,
            'km_realizado' => $this->kmRealizado($this->vehiculo_id, $this->created_at),
            'fecha' => date(Utils::MASKFECHA, strtotime($this->created_at)),
            'servicios' => $this->servicios ? ServicioResource::collection(Servicio::whereIn('id', array_map('intval', Utils::convertirStringComasArray($this->servicios)))->get())->map(function ($item) {
                return $item->nombre;
            }) : null,

        ];

        if ($controller_method == 'show') {
            $modelo['servicios'] = $this->servicios ? array_map('intval', Utils::convertirStringComasArray($this->servicios)) : null;
            $modelo['solicitante_id'] = $this->solicitante_id;
            $modelo['autorizador'] = $this->autorizador_id;
            $modelo['autorizacion'] = $this->autorizacion_id;
            $modelo['vehiculo_id'] = $this->vehiculo_id;
            $modelo['observacion'] = $this->observacion;
        }

        return $modelo;
    }
}
