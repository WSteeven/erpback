<?php

namespace App\Http\Resources\ActivosFijos;

use App\Http\Resources\Bodega\PermisoArmaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivoFijoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $controller_method = request()->route()->getActionMethod();

        $detalleProducto = $this->detalleProducto;

        $modelo = [
            'id' => $this->id,
            'codigo_inventario' => 'AF' . $this->id, // PENDIENTE
            'descripcion' => $detalleProducto->descripcion,
            'serie' => $detalleProducto->serial,
            'fecha_caducidad' => $this->fecha_caducidad,
            'unidad_medida' => $detalleProducto->producto->unidadMedida->nombre,
            'egresos' => $this->egresos,
            'etiqueta_personalizada' => $this->etiqueta_personalizada,
        ];

        if ($controller_method == 'show') {
            $modelo['tipo'] = $detalleProducto->tipo;
            $modelo['marca'] = $detalleProducto->marca?->nombre;
            $modelo['modelo'] = $detalleProducto->modelo?->nombre;
            $modelo['calibre'] = $detalleProducto->calibre;
            $modelo['fotografia'] = $detalleProducto->fotografia ? url($detalleProducto->fotografia) : null;
            $modelo['fotografia_detallada'] = $detalleProducto->fotografia_detallada ? url($detalleProducto->fotografia_detallada) : null;
            $modelo['permiso_arma'] = new PermisoArmaResource($this->detalleProducto->permisoArma);
        }

        return $modelo;
    }
}
