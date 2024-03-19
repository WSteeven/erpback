<?php

namespace App\Http\Resources\ComprasProveedores;

use App\Models\ComprasProveedores\PagoProveedores;
use Illuminate\Http\Resources\Json\JsonResource;

class PagoProveedoresResource extends JsonResource
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
            'realizador' => $this->realizador->nombres . ' ' . $this->realizador->apellidos,
            'estado' => $this->estado_bloqueado,
            'cant_elementos' => $this->items()->count(),
            'created_at' => date('Y-m-d h:i:s a', strtotime($this->created_at)),
        ];

        if ($controller_method == 'show') {
            // $modelo['realizador'] = $this->realizador_id;
            $modelo['listado'] = ItemPagoProveedoresResource::collection($this->items);
        }

        return $modelo;
    }
}
