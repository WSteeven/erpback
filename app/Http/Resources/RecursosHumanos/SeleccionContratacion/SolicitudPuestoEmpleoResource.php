<?php

namespace App\Http\Resources\RecursosHumanos\SeleccionContratacion;

use App\Models\Empleado;
use Illuminate\Http\Resources\Json\JsonResource;

class SolicitudPuestoEmpleoResource extends JsonResource
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
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'anios_experiencia' => $this->anios_experiencia,
            'tipo_puesto' => $this->tipoPuesto->nombre,
            'tipo_puesto_info' => $this->tipoPuesto,
            'cargo' => $this->cargo_id,
            'cargo_info' => $this->cargo,
            'autorizador' => Empleado::extraerNombresApellidos($this->autorizador),
            'autorizacion' => $this->autorizacion->nombre,
        ];
        if ($controller_method == 'show') {
            $modelo['tipo_puesto'] = $this->tipo_puesto_id;
            $modelo['autorizador'] = $this->autorizador_id;
            $modelo['autorizacion'] = $this->autorizacion_id;
            $modelo['cargo'] = $this->cargo_id;
            $modelo['requiere_experiencia'] = !!$this->anios_experiencia;
        }
        return $modelo;
    }
}
