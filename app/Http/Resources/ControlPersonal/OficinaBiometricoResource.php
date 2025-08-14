<?php

namespace App\Http\Resources\ControlPersonal;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OficinaBiometricoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $controllerMethod = $request->route()->getActionMethod();
        $modelo = [
            'id'=>$this->id,
            'nombre'=>$this->nombre,
            'descripcion'=>$this->descripcion,
            'direccion'=>$this->direccion,
            'latitud'=>$this->latitud,
            'longitud'=>$this->longitud,
            'direccion_ip'=>$this->direccion_ip,
            'puerto'=>$this->puerto, //opcional
            'canton'=>$this->canton?->canton,
            'activo'=>$this->activo,
        ];

        if ($controllerMethod == 'show') {
            $modelo['canton'] = $this->canton_id;
        }

        return $modelo;
    }
}
