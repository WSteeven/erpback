<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificacionResource extends JsonResource
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
            'mensaje'=>$this->mensaje,
            'link'=>$this->link,
            'per_originador'=>$this->originador->nombres.' '.$this->originador->apellidos,
            'per_destinatario'=>$this->destinatario->nombres.' '.$this->destinatario->apellidos,
            'leida'=>$this->leidaP
        ];

        if($controller_method =='show'){
            $modelo['per_originador'] = $this->per_originador_id;
            $modelo['per_destinatario_id'] = $this->per_destinatario_id;
        }

        return $modelo;
    }
}
