<?php

namespace App\Http\Resources\Vehiculos;

use App\Models\Empleado;
use App\Models\Vehiculos\Servicio;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\Shared\Utils;

class OrdenReparacionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $controller_method = $request->route()->getActionMethod();
        $modelo = [
            'id' => $this->id,
            'solicitante' => $this->solicitante->nombres . ' ' . $this->solicitante->apellidos,
            'autorizador' => Empleado::extraerNombresApellidos($this->autorizador),
            'vehiculo' => $this->vehiculo->placa,
            'autorizacion' => $this->autorizacion->nombre,
            'observacion' => $this->observacion,
            'km_realizado' => $this->kmRealizado($this->vehiculo_id, $this->created_at),
            'fecha' =>$this->fecha,
            'servicios' => $this->servicios ? ServicioResource::collection(Servicio::whereIn('id', array_map('intval', Utils::convertirStringComasArray($this->servicios)))->get())->map(function ($item) {
                return $item->nombre;
            }) : null,
            'valor_reparacion' => $this->valor_reparacion,
            'motivo' => $this->motivo,
            'num_factura' => $this->num_factura,
        ];

        if ($controller_method == 'show') {
            $modelo['servicios'] = $this->servicios ? array_map('intval', Utils::convertirStringComasArray($this->servicios)) : null;
            $modelo['solicitante'] = $this->solicitante_id;
            $modelo['solicitante_id'] = $this->solicitante_id;
            $modelo['autorizador'] = $this->autorizador_id;
            $modelo['autorizacion'] = $this->autorizacion_id;
            $modelo['vehiculo'] = $this->vehiculo_id;
            $modelo['vehiculo_id'] = $this->vehiculo_id;
            $modelo['observacion'] = $this->observacion;
        }

        return $modelo;
    }
}
