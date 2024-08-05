<?php

namespace App\Http\Resources\RecursosHumanos\SeleccionContratacion;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\Shared\Utils;


class SolicitudPersonalResource extends JsonResource
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
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'anios_experiencia' => $this->anios_experiencia,
            'tipo_puesto' => $this->tipoPuesto->nombre,
            'tipo_puesto_info' => $this->tipoPuesto,
            'cargo' => $this->cargo?->nombre,
            'cargo_info' => $this->cargo,
            'solicitante' => Empleado::extraerNombresApellidos($this->solicitante),
            'autorizador' => Empleado::extraerNombresApellidos($this->autorizador),
            'autorizacion' => $this->autorizacion->nombre,
            'disponibilidad_viajar'=>$this->disponibilidad_viajar,
            'requiere_licencia'=>$this->requiere_licencia,
        ];
        if ($controller_method == 'show') {
            $modelo['tipo_puesto'] = $this->tipo_puesto_id;
            $modelo['modalidad'] = $this->modalidad_id;
            $modelo['solicitante'] = $this->solicitante_id;
            $modelo['autorizador'] = $this->autorizador_id;
            $modelo['autorizacion'] = $this->autorizacion_id;
            $modelo['cargo'] = $this->cargo_id;
            $modelo['areas_conocimiento'] = $this->areas_conocimiento ? array_map('intval', Utils::convertirStringComasArray($this->areas_conocimiento)) : [];
            $modelo['requiere_experiencia'] = !!$this->anios_experiencia;
            $modelo['formaciones_academicas'] = $this->formacionesAcademicas;
            $modelo['requiere_formacion_academica'] = !!count($this->formacionesAcademicas);

        }
        return $modelo;
    }
}
