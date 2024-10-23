<?php

namespace App\Http\Resources\RecursosHumanos;

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
            'empleado_id'=> $this->empleado_id,
            'periodo' => $this->periodo->nombre,
            'dias' => $this->dias,
            'opto_pago' => $this->opto_pago,
            'completadas' => $this->completadas,
            'dias_tomados'=>0, //esto es calculo
            'dias_disponibles'=>$this->dias - 0, // esto tambien es calculado
        ];

        if ($controller_method == 'show') {
            $modelo['empleado'] = $this->empleado_id;
            $modelo['fecha_ingreso'] = $this->empleado->fecha_ingreso;
            $modelo['periodo'] = $this->periodo_id;
        }
        return $modelo;
    }
}
