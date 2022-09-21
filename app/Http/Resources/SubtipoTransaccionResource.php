<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubtipoTransaccionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);

        $controller_method = $request->route()->getActionMethod();
        $modelo = [
            'id'=>$this->id,
            'nombre'=>$this->nombre,
            'tipo_transaccion'=>$this->tipo_transaccion->nombre,
        ];

        if($controller_method=='show'){
            $modelo['tipo_transaccion'] = $this->tipo_transaccion_id;
        }

        return $modelo;
    }
}
