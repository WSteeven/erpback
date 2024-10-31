<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VacacionResource extends JsonResource
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
            'empleado' => Empleado::extraerNombresApellidos(Empleado::find($this->empleado_id)),
            'empleado_id' => $this->empleado_id,
            'periodo' => $this->periodo->nombre,
            'periodo_id' => $this->periodo_id,
            'dias' => $this->dias,
            'opto_pago' => $this->opto_pago,
            'completadas' => $this->completadas,
            'dias_tomados' => $this->detalles()->sum('dias_utilizados'), //esto es calculo
            'detalles' => DetalleVacacionResource::collection($this->detalles()->get()),
            'dias_disponibles' => $this->dias - $this->detalles()->sum('dias_utilizados'), // esto tambien es calculado
        ];

        if ($controller_method == 'show') {
            $modelo['empleado'] = $this->empleado_id;
            $modelo['fecha_ingreso'] = $this->empleado->fecha_ingreso;
            $modelo['periodo'] = $this->periodo_id;
        }
        return $modelo;
    }
}
