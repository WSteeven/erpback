<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SucursalResource extends JsonResource
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

        $modelo =  [
            'id' => $this->id,
            'lugar' => $this->lugar,
            'telefono' => $this->telefono,
            'correo' => $this->correo,
            'extension' => $this->extension,
            'cliente'=>$this->cliente?->empresa->razon_social,
            'activo'=>$this->activo,
        ];

        if($controller_method=='show'){
            $modelo['cliente'] = $this->cliente_id;
        }

        return $modelo;
    }
}
