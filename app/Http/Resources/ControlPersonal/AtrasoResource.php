<?php

namespace App\Http\Resources\ControlPersonal;

use App\Models\Empleado;
use Carbon\CarbonInterval;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AtrasoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public function toArray($request)
    {
        $controller_method = $request->route()->getActionMethod();
        $modelo = [
            'id' => $this->id,
            'empleado' => Empleado::extraerNombresApellidos($this->empleado),
            'justificador' => Empleado::extraerNombresApellidos($this->justificador),
            'marcacion' => $this->marcacion_id,
            'fecha_atraso' => $this->fecha_atraso,
            'ocurrencia' => $this->ocurrencia,
            'tiempo_atraso' => CarbonInterval::seconds($this->segundos_atraso)->cascade()->forHumans(),
            'segundos_atraso' => $this->segundos_atraso,
            'justificado' => $this->justificado,
            'justificacion' => $this->justificacion ?: '',
            'imagen_evidencia' => $this->imagen_evidencia ? url($this->imagen_evidencia) : null,
            'revisado' => $this->revisado,
        ];


        if ($controller_method == 'show') {
            $modelo['empleado'] = $this->empleado_id;
            $modelo['justificador'] = $this->justificador_id;
            $modelo['marcacion'] = $this->marcacion_id;
        }


        return $modelo;
    }
}
