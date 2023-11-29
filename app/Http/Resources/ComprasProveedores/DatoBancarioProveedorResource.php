<?php

namespace App\Http\Resources\ComprasProveedores;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class DatoBancarioProveedorResource extends JsonResource
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
        $modelo  = [
            'id' => $this->id,
            'banco' => $this->banco->nombre,
            'empresa' => $this->empresa->razon_social,
            'tipo_cuenta' => $this->tipo_cuenta,
            'numero_cuenta' => $this->numero_cuenta,
            'identificacion' => $this->identificacion,
            'nombre_propietario' => $this->nombre_propietario,
        ];

        if($controller_method =='show' && $request->route()->uri() == 'api/compras/datos-bancarios-proveedores/{dato}'){
            $modelo['banco'] = $this->banco_id;
            $modelo['empresa'] = $this->empresa_id;
        }


        return $modelo;
    }
}
