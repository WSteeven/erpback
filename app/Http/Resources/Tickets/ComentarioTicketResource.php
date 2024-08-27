<?php

namespace App\Http\Resources\Tickets;

use App\Models\Empleado;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ComentarioTicketResource extends JsonResource
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
            'id' => $this->id,
            'ticket' => $this->ticket_id,
            'comentario' => [$this->comentario],
            'empleado' => $this->empleado ? Empleado::extraerNombresApellidos($this->empleado) : null,
            'avatar' => $this->empleado->foto_url ? url($this->empleado->foto_url) : url('/storage/sinfoto.png'),
            'stamp' => 'Hace ' . CarbonInterval::seconds(Carbon::now()->diffInSeconds(Carbon::parse($this->created_at)))->cascade()->forHumans(),
            'sent' => Auth::user()->empleado->id === $this->empleado_id,
        ];
    }
}
