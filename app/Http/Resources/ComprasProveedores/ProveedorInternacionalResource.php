<?php

namespace App\Http\Resources\ComprasProveedores;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProveedorInternacionalResource extends JsonResource
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
            'id'=> $this->id,
            'nombre'=> $this->nombre,
            'tipo'=> $this->tipo,
            'ruc'=> $this->ruc,
            'pais'=> $this->pais->pais,
            'direccion'=> $this->direccion,
            'telefono'=> $this->telefono,
            'correo'=> $this->correo,
            'sitio_web'=> $this->sitio_web,
            'banco1'=> $this->banco1,
            'numero_cuenta1'=> $this->numero_cuenta1,
            'codigo_swift1'=> $this->codigo_swift1,
            'moneda1'=> $this->moneda1,
            'banco2'=> $this->banco2,
            'numero_cuenta2'=> $this->numero_cuenta2,
            'codigo_swift2'=> $this->codigo_swift2,
            'moneda2'=> $this->moneda2,
            'activo'=> $this->activo,
        ];
        if ($controller_method == 'show') {
            $modelo['pais'] = $this->pais_id;
        }

        return $modelo;
    }
}
