<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanVacacionResource extends JsonResource
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
            'periodo' => $this->periodo->nombre,
            'periodo_id' => $this->periodo_id,
            'empleado' => Empleado::extraerNombresApellidos($this->empleado),
            'rangos' => $this->rangos,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'fecha_inicio_primer_rango' => $this->fecha_inicio_primer_rango,
            'fecha_fin_primer_rango' => $this->fecha_fin_primer_rango,
            'fecha_inicio_segundo_rango' => $this->fecha_inicio_segundo_rango,
            'fecha_fin_segundo_rango' => $this->fecha_fin_segundo_rango,
            'dias_primer_rango' =>$this->obtenerCantidadDias($this->fecha_inicio_primer_rango, $this->fecha_fin_primer_rango),
            'dias_segundo_rango' =>$this->obtenerCantidadDias($this->fecha_inicio_segundo_rango, $this->fecha_fin_segundo_rango),
        ];

        if ($controller_method == 'show') {
            $modelo['periodo'] = $this->periodo_id;
            $modelo['empleado'] = $this->empleado_id;
        }

        return $modelo;
    }

    private function obtenerCantidadDias(string|null $fecha_inicio, string|null $fecha_fin)
    {
        if ($fecha_inicio !== null && $fecha_fin !== null) {
            $fecha_inicio = Carbon::parse($fecha_inicio);
            $fecha_fin = Carbon::parse($fecha_fin);
            return $fecha_inicio->diffInDays($fecha_fin) +1; //se suma uno ya que el metodo `diffInDays` solo resta la cantidad de dias sin incluir el dia inicial
        } else return 0;
    }
}
