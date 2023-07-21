<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SeguimientoSubtareaResource extends JsonResource
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
            'trabajo_realizado' => $this->mapTrabajoRealizado(),
            'observaciones' => $this->observaciones,
            'materiales_tarea_ocupados' => $this->subtarea?->seguimientosMaterialesSubtareas()->whereDate('created_at', Carbon::now()->format('Y-m-d'))->get(),// ?? [],//materiales_tarea_ocupados,
            // 'historial_material_tarea_usado' => $this->subtarea?->seguimientosMaterialesSubtareas()->whereDate('created_at', Carbon::now()->format('Y-m-d'))->get(),
            'materiales_stock_ocupados' => $this->materiales_stock_ocupados,
            'materiales_devolucion' => $this->materiales_devolucion,
            'subtarea' => $this->subtarea_id,
        ];
    }

    private function mapTrabajoRealizado()
    {
        return $this->trabajoRealizado->map(fn ($trabajo) => [
            'id' => $trabajo->id,
            'fecha_hora' => Carbon::parse($trabajo->fecha_hora)->format('d-m-Y H:i:s'),
            'fotografia' => $trabajo->fotografia ? $this->imagenBase64($trabajo->fotografia) : null,
            'trabajo_realizado' => $trabajo->trabajo_realizado,
        ]);
    }

    private function imagenBase64($fotografia)
    {
        return 'data:image/png;base64,' . base64_encode(file_get_contents(url($fotografia)));
    }
}
