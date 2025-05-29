<?php

namespace App\Http\Resources\Ventas;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseComisionResource extends JsonResource
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
            'modalidad' => $this->modalidad?->nombre,
            'modalidad_id' => $this->modalidad_id,
            'presupuesto_ventas' => $this->presupuesto_ventas,
            'bono_comision_semanal' => $this->bono_comision_semanal,
            'comisiones' => array_map(function ($comision) {
                if($comision['desde']===$comision['hasta'])
                    return ' '.$comision['desde'] . ': ' . $comision['comision'] . "%TB.";
                return ' '.$comision['desde'] . '-' . $comision['hasta'] . ': ' . $comision['comision'] . "%TB.";
            }, $this->comisiones),
        ];


        if ($controller_method == 'show') {
            $modelo['modalidad'] = $this->modalidad_id;
            $modelo['comisiones'] = $this->comisiones;
        }

        return $modelo;
    }
}
