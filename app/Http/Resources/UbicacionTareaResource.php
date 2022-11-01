<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class UbicacionTareaResource extends JsonResource
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

        Log::channel('testing')->info('Log', ['Datos ubicacion', $request]);

        $modelo = [
            'id' => $this->id,
            'parroquia' => $this->parroquia,
            'direccion' => $this->direccion,
            'referencias' => $this->referencias,
            'coordenadas' => $this->coordenadas,
            'provincia' => $this->provincia->provincia, //->provincia,
            'canton' => $this->canton->canton,
            'tarea' => $this->tarea_id,
        ];

        if ($controller_method == 'show') {
            $modelo['provincia'] = $this->provincia_id;
            $modelo['canton'] = $this->canton_id;
        }

        return $modelo;
    }
}
