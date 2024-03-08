<?php

namespace App\Http\Resources\FondosRotativos;

use Illuminate\Http\Resources\Json\JsonResource;

class AjusteSaldoFondoRotativoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'solicitante_id',
            'destinatario_id',
            'autorizador_id',
            'motivo',
            'descripcion',
            'monto',
            'tipo',
        ];
    }
}
