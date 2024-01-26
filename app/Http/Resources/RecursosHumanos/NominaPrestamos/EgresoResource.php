<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class EgresoResource extends JsonResource
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
        $tipo ='';
        if ($this->descuento_type === "App\\Models\\RecursosHumanos\\NominaPrestamos\\DescuentosGenerales") {
            $tipo= "DESCUENTO_GENERAL";
        } elseif ($this->descuento_type === "App\\Models\\RecursosHumanos\\NominaPrestamos\\Multas") {
            $tipo = "MULTA";
        }
        $modelo = [
            'id' => $this->id,
            'empleado' => $this->empleado_id,
            'empleado_info' => $this->empleado != null? $this->empleado->apellidos. ' ' .$this->empleado->nombres:'',
            'tipo' => $tipo,
            'descuento' => $this->descuento->nombre,
            'monto' => $this->monto,
            'id_descuento' => $this->descuento->id
        ];
        return  $modelo;
    }
}
