<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class ParroquiaResource extends JsonResource
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
            'parroquia' => $this->parroquia,
            'provincia' => $this->canton?->provincia?->provincia,
            'provincia_id' => $this->canton?->provincia_id,
            'canton' => $this->canton?->canton,
            'canton_id' => $this->canton_id,
        ];


        if ($controller_method == 'show') {
            // $modelo['categoria'] = $this->categoria->nombre;
            $modelo['provincia'] = $this->canton?->provincia_id;
            $modelo['canton'] = $this->canton_id;
        }

        return $modelo;
    }
}
