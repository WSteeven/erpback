<?php

namespace App\Http\Resources\FondosRotativos\Gasto;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AutorizadorDirectoResource extends JsonResource
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
            'id'=>$this->id,
            'empleado'=>Empleado::extraerNombresApellidos($this->empleado),
            'empleado_id'=>$this->empleado_id,
            'autorizador'=>Empleado::extraerNombresApellidos($this->autorizador),
            'autorizador_id'=>$this->autorizador_id,
            'observacion'=>$this->observacion,
            'activo' =>$this->activo
        ];

        if($controller_method == 'show'){
            $modelo['empleado']= $this->empleado_id;
            $modelo['autorizador']= $this->autorizador_id;
        }


        return $modelo;
    }
}
