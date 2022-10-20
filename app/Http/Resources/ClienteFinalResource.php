<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClienteFinalResource extends JsonResource
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
            'id_cliente' => $this->id_cliente,
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'celular' => $this->celular,
            'parroquia' => $this->parroquia,
            'direccion' => $this->direccion,
            'referencias' => $this->referencias,
            'coordenadas' => $this->coordenadas,
            'provincia' => $this->provincia->nombre,
            'canton' => $this->canton->nombre,
            'cliente' => $this->cliente->empresa->razon_social,
        ];

        if ($controller_method == 'show') {
            $modelo['provincia'] = $this->provincia_id;
            $modelo['canton'] = $this->canton_id;
            $modelo['cliente'] = $this->cliente_id;
        }

        return $modelo;
    }
}
