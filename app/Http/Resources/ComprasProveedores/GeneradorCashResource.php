<?php

namespace App\Http\Resources\ComprasProveedores;

use App\Models\Empleado;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class GeneradorCashResource extends JsonResource
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
            'id' => $this['id'],
            'titulo' => $this['titulo'],
            'creador' => Empleado::extraerNombresApellidos($this->creador),
            'created_at' => Carbon::parse($this['created_at'])->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::parse($this['updated_at'])->format('Y-m-d H:i:s'),
            'valor_total' => number_format($this->pagos()->sum('valor'), 2),
            'total_pagos' => $this->pagos()->count(),
        ];

        if ($controller_method == 'show') {
            $modelo['pagos'] = PagoResource::collection($this->pagos()->latest()->get());
        }

        return $modelo;
    }
}
