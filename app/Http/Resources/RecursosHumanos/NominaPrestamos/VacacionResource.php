<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\Vacacion;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\App\RecursosHumanos\NominaPrestamos\VacacionService;

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
            'opto_pago' => $this->opto_pago,
            'completadas' => $this->completadas,
            'dias_tomados' => $this->detalles()->sum('dias_utilizados'), //esto es calculo
            'detalles' => DetalleVacacionResource::collection($this->detalles()->get()),
//            'dias_disponibles' => $this->dias - $this->detalles()->sum('dias_utilizados'), // esto tambien es calculado
            'dias_disponibles' => VacacionService::calcularDiasDeVacacionesPeriodoSeleccionado(Vacacion::find($this->id)), // esto tambien es calculado
//            'dias' => $this->dias,
            'dias' =>$this->detalles()->sum('dias_utilizados') + VacacionService::calcularDiasDeVacacionesPeriodoSeleccionado(Vacacion::find($this->id)),
            'observacion'=>$this->observacion,
            'mes_pago'=>$this->mes_pago,
        ];

        if ($controller_method == 'show') {
            $modelo['empleado'] = $this->empleado_id;
            $modelo['fecha_ingreso'] = $this->empleado->fecha_ingreso;
            $modelo['periodo'] = $this->periodo_id;
        }
        return $modelo;
    }
}
