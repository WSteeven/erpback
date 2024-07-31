<?php

namespace App\Http\Resources\Intranet;

use App\Models\Empleado;
use Illuminate\Http\Resources\Json\JsonResource;

class EventoResource extends JsonResource
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
        $modelo = [
            'id' => $this->id,
            'titulo'=>$this->titulo,
            'tipo_evento_id'=>$this->tipo_evento_id,
            'colorScheme'=>$this->tipoEvento->nombre,
            'anfitrion_id'=>$this->anfitrion_id,
            'anfitrion'=>Empleado::extraerNombresApellidos($this->anfitrion),
            'descripcion'=>$this->descripcion,
            'fecha_hora_inicio'=>date('Y-m-d h:i',strtotime($this->fecha_hora_inicio)),
            'fecha_hora_fin'=>date('Y-m-d h:i', strtotime($this->fecha_hora_fin)),
            'es_editable'=>$this->es_editable,
            'es_personalizado'=>$this->es_personalizado,
        ];
        if($controller_method=='show'){
            $modelo['tipo_evento'] = $this->tipo_evento_id;
        }

        return $modelo;
    }
}
