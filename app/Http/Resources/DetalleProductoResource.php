<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DetalleProductoResource extends JsonResource
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

        $modelo =  [
            'categoria' => $this->producto->categoria->nombre,

            'id' => $this->id,
            'producto' => $this->producto->nombre,
            'producto_id' => $this->producto_id,
            'descripcion' => $this->descripcion,
            'marca' => $this->modelo->marca->nombre,
            'modelo' => $this->modelo->nombre,
            'serial' => $this->serial,
            'precio_compra' => $this->precio_compra,

            // 'computadora'=>$this->computadora,

            'ram' => $this->computadora ? $this->computadora->memoria->nombre : null,
            'disco' => $this->computadora ? $this->computadora->disco->nombre : null,
            'procesador' => $this->computadora ? $this->computadora->procesador->nombre : null,

            'computadora' => $this->computadora ? $this->computadora->memoria->nombre . ' RAM, ' . $this->computadora->disco->nombre . ', ' . $this->computadora->procesador->nombre : null,
            'fibra' => $this->fibra ? 'Span ' . $this->fibra->span->nombre . ', ' . $this->fibra->hilo->nombre . 'H, ' . $this->fibra->tipo_fibra->nombre : null,

            'span' => $this->fibra ? $this->fibra->span->nombre : 'N/A',
            'tipo_fibra' => $this->fibra ? $this->fibra->tipo_fibra->nombre : null,
            'hilos' => $this->fibra ?  $this->fibra->hilo->nombre : null,
            'punta_inicial' => $this->fibra ? $this->fibra->punta_inicial : null,
            'punta_final' => $this->fibra ? $this->fibra->punta_final : null,
            'custodia' => $this->fibra ? $this->fibra->custodia : null,
            'puntas' => $this->fibra ? 'P. Inicial: ' . $this->fibra->punta_inicial . ', P. Final: ' . $this->fibra->punta_final . ', Custodia: ' . $this->fibra->custodia : null,


            'adicionales' => $this->color || $this->talla || $this->capacidad ? $this->color . ', ' . $this->talla . ',  ' . $this->capacidad : null,

            'color'=>$this->color,
            'talla'=>$this->talla,
            'capacidad'=>$this->capacidad,

            //variables auxiliares
            'tiene_serial' => is_null($this->serial) ? false : true,
            'es_computadora' => $this->producto->categoria->nombre == 'INFORMATICA' ? true : false,
            'es_fibra' => $this->fibra ? true : false,
            'tiene_precio_compra' => $this->precio_compra > 0 ? true : false,
            'tiene_adicionales' => $this->color || $this->talla || $this->capacidad ? true : false,
        ];
        if ($controller_method == 'show') {
            $modelo['producto'] = $this->producto_id;
            $modelo['modelo'] = $this->modelo_id;
            $modelo['span'] =  $this->fibra ? $this->fibra->span_id : null;
            $modelo['tipo_fibra'] =  $this->fibra ? $this->fibra->tipo_fibra_id : null;
            $modelo['hilos'] = $this->fibra ? $this->fibra->hilo_id : null;
            $modelo['ram'] = $this->computadora ? $this->computadora->memoria->id : null;
            $modelo['disco'] = $this->computadora ? $this->computadora->disco->id : null;
            $modelo['procesador'] = $this->computadora ? $this->computadora->procesador->id : null;
        }
        return $modelo;
    }
}
