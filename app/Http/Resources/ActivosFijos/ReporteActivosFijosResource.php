<?php

namespace App\Http\Resources\ActivosFijos;

use App\Models\MaterialEmpleado;
use Illuminate\Http\Resources\Json\JsonResource;

class ReporteActivosFijosResource extends JsonResource
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
            'descripcion' => $this->descripcion,
            'factura_compra' => $this->obtenerFacturaCompra(),
            'serial' => $this->serial,
            'responsables' => $this->obtenerResponsable(),
            'ciudad_responsable' => $this->obtenerCiudadResponsable(),
        ];
    }

    private function obtenerFacturaCompra()
    {
        return null;
    }

    private function obtenerCiudadResponsable()
    {
        return null;
    }

    private function obtenerResponsable()
    {
        $materiales = MaterialEmpleado::where('detalle_producto_id', $this->id)->get();
        return $materiales->map(fn($material) => [
            'empleado_id' => $material->empleado_id,
            'cliente_id' => $material->cliente_id,
        ]);
    }
}
