<?php

namespace App\Http\Resources\ActivosFijos;

use App\Http\Resources\Bodega\PermisoArmaResource;
use App\Http\Resources\DetalleProductoResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivoFijoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $controller_method = request()->route()->getActionMethod();

        $detalleProducto = $this->detalleProducto;

        $modelo = [
            'id' => $this->id,
            'codigo' => str_pad($this->id, 6, '0', STR_PAD_LEFT),
            'descripcion' => $detalleProducto->descripcion,
            'serie' => $detalleProducto->serial,
            'fecha_caducidad' => $detalleProducto->fecha_caducidad,
            'unidad_medida' => $detalleProducto->producto->unidadMedida->nombre,
            'detalle_producto' => new DetalleProductoResource($detalleProducto),
            'cliente' => $this->cliente->empresa->razon_social,
            'codigo_producto' => $detalleProducto->id,
            'codigo_personalizado' => $this->codigo_personalizado,
            'codigo_sistema_anterior' => $this->codigo_sistema_anterior,
            'marca' => $detalleProducto->marca?->nombre,
        ];

        if ($controller_method == 'show') {
            $modelo['tipo'] = $detalleProducto->tipo;
            $modelo['modelo'] = $detalleProducto->modelo?->nombre;
            $modelo['calibre'] = $detalleProducto->calibre;
            $modelo['cliente_id'] = $this->cliente_id;
            $modelo['fotografia'] = $detalleProducto->fotografia ? url($detalleProducto->fotografia) : null;
            $modelo['fotografia_detallada'] = $detalleProducto->fotografia_detallada ? url($detalleProducto->fotografia_detallada) : null;
            $modelo['permiso_arma'] = new PermisoArmaResource($this->detalleProducto->permisoArma);
        }

        return $modelo;
    }
}
