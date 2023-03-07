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
            'id_cliente_final' => $this->id_cliente_final,
            'cliente' => $this->cliente->empresa->razon_social,
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'celular' => $this->celular,
            'parroquia' => $this->parroquia,
            'direccion' => $this->direccion,
            'referencia' => $this->referencia,
            'coordenada_latitud' => $this->coordenada_latitud,
            'coordenada_longitud' => $this->coordenada_longitud,
            'provincia' => $this->provincia?->provincia,
            'canton' => $this->canton?->canton,
        ];

        if ($controller_method == 'show') {
            $modelo['provincia'] = $this->provincia_id;
            $modelo['canton'] = $this->canton_id;
            $modelo['cliente'] = $this->cliente_id;
        }

        return $modelo;
    }
}
