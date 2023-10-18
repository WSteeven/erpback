<?php

namespace App\Http\Resources\FondosRotativos\Saldo;

use Illuminate\Http\Resources\Json\JsonResource;

class ValorAcreditarResource extends JsonResource
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
            'id'=>$this->id,
            'empleado'=>$this->empleado->nombres.' '.$this->empleado->apellidos,
            'monto_generado'=>$this->monto_generado,
            'monto_modificado'=>$this->monto_modificado,
            'acreditacion_semana'=>$this->acreditacion_semanal->semana,
        ];
        return $modelo;
    }
}
