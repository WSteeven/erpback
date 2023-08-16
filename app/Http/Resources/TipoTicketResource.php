<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TipoTicketResource extends JsonResource
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
            'nombre' => $this->nombre,
            'activo' => $this->activo,
            'categoria_tipo_ticket' => $this->categoriaTipoTicket?->nombre,
            'categoria_tipo_ticket_id' => $this->categoria_tipo_ticket_id,
            'departamento' => $this->categoriaTipoTicket?->departamento->nombre,
        ];

        if ($controller_method == 'show') {
            $modelo['departamento'] = $this->categoriaTipoTicket?->departamento_id;
            $modelo['categoria_tipo_ticket'] = $this->categoria_tipo_ticket_id;
        }

        return $modelo;
    }
}
