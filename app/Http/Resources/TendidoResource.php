<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TendidoResource extends JsonResource
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
            'marca_inicial' => $this->marca_inicial,
            'marca_final' => $this->marca_final,
            'fecha' => Carbon::parse($this->created_at)->format('d-m-Y  H:i:s'),
            'trabajo' => $this->trabajo->codigo_trabajo,
            'tarea' => $this->trabajo->tarea_id,
            // 'bobina' => $this->bobina->descripcion,
        ];

        if ($controller_method == 'show') {
            $modelo['trabajo'] = $this->trabajo_id;
            $modelo['bobina'] = $this->bobina_id;
        }

        return $modelo;
    }
}
