<?php

namespace App\Http\Resources\Tickets;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ComentarioTicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'ticket' => $this->ticket_id,
            'comentario' => [$this->comentario],
            'empleado' => $this->empleado ? Empleado::extraerNombresApellidos($this->empleado) : null,
            'avatar' => $this->empleado->foto_url ? url($this->empleado->foto_url) : url('/storage/sinfoto.png'),
            'stamp' => $this->created_at,
            'sent' => Auth::user()->empleado->id === $this->empleado_id,
            'adjuntos' => collect($this->adjuntos ?? [])
                ->filter(fn ($a) => is_array($a) && isset($a['url']))
                ->map(fn ($a) => [
                    'nombre' => $a['nombre'] ?? pathinfo($a['url'], PATHINFO_BASENAME),
                    'tipo'   => $a['tipo'] ?? null,
                    'url'    => asset($a['url']),
                ])
                ->values()
                ->all(),
        ];
    }
}
