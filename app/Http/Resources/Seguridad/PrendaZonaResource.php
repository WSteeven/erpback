<?php

namespace App\Http\Resources\Seguridad;

use App\Models\Empleado;
use Illuminate\Http\Resources\Json\JsonResource;

class PrendaZonaResource extends JsonResource
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
            'zona' => $this->zona->nombre,
            'empleado_id' => $this->empleado_id,
            'empleado_apellidos_nombres' => Empleado::extraerApellidosNombres($this->empleado),
            'cliente_id' => $this->cliente_id,
            'tiene_restricciones' => $this->tiene_restricciones,
        ];

        if ($controller_method == 'show') {
            $modelo['zona'] = $this['zona_id'];
            $modelo['detalles_productos'] = json_decode($this['detalles_productos']);
        }

        return $modelo;
    }
}
