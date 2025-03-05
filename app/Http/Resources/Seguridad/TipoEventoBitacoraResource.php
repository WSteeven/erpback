<?php

namespace App\Http\Resources\Seguridad;

use Illuminate\Http\Resources\Json\JsonResource;

class TipoEventoBitacoraResource extends JsonResource
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
            'nombre' => $this['nombre'],
            'descripcion' => $this['descripcion'],
            'activo' => $this['activo'],
            'notificacion_inmediata_texto' => $this['notificacion_inmediata'] ? 'SI' : 'NO',
            'notificacion_inmediata' => $this['notificacion_inmediata'],
        ];

        if ($controller_method == 'show') {
            $modelo['notificacion_inmediata'] = $this['notificacion_inmediata'];
        }

        return $modelo;
    }
}
