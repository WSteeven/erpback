<?php

namespace App\Http\Resources\Tareas;

use App\Http\Resources\GrupoResource;
use App\Models\Empleado;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NodoResource extends JsonResource
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
            'id'=>$this->id,
            'grupos'=>Grupo::whereIn('id', $this->grupos)->pluck('nombre'),
//            'grupos'=> $this->grupos,
            'coordinador'=>Empleado::extraerNombresApellidos($this->coordinador),
            'nombre'=>$this->nombre,
            'activo'=>$this->activo,
        ];

        if ($controller_method == 'show') {
            $modelo['coordinador'] = $this->coordinador_id;
            $modelo['grupos'] = $this->grupos;
        }
        return $modelo;
    }
}
