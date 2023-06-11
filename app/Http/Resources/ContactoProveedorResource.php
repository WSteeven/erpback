<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class ContactoProveedorResource extends JsonResource
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
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'nombres_completos' => $this->nombres . ' ' . $this->apellidos,
            'celular' => $this->celular,
            'ext' => $this->ext,
            'correo' => $this->correo,
            'tipo_contacto' => $this->tipo_contacto,
            'proveedor' => $this->proveedor->empresa->razon_social . ' - ' . $this->proveedor->sucursal,
        ];
        if ($controller_method == 'show') {
            $modelo['proveedor'] = $this->proveedor_id;
        }

        return $modelo;
    }
}
