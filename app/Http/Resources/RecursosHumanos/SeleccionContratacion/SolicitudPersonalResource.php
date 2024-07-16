<?php

namespace App\Http\Resources\RecursosHumanos\SeleccionContratacion;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class SolicitudPersonalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
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
            $modelo['areas_conocimiento'] = json_decode($this->areas_conocimiento);
            $modelo['requiere_experiencia'] = !!$this->anios_experiencia;
            $modelo['formaciones_academicas'] = $this->formacionesAcademicas;
            $modelo['requiere_formacion_academica'] = !!$this->formacionesAcademicas;

        }
        return $modelo;
    }
}
