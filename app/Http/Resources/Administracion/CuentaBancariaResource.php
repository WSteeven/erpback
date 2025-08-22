<?php

namespace App\Http\Resources\Administracion;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CuentaBancariaResource extends JsonResource
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
            'es_principal' => $this->es_principal,
            'banco' => $this->banco->nombre,
            'tipo_cuenta' => $this->tipo_cuenta,
            'numero_cuenta' => $this->numero_cuenta,
            'observacion' => $this->observacion,
        ];

        if ($controller_method == 'show') {
            $modelo['banco'] = $this->banco_id;
        }
        return $modelo;
    }
}
