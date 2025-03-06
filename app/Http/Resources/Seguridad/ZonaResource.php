<?php

namespace App\Http\Resources\Seguridad;

use App\Models\Cargo;
use App\Models\Empleado;
use Illuminate\Http\Resources\Json\JsonResource;

class ZonaResource extends JsonResource
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
            'direccion' => $this['direccion'],
            'coordenadas' => $this['coordenadas'],
            'activo' => $this['activo'],
            // 'empleados_asignados' => $this->empleados,//json_decode($this['empleados_asignados_ids']),
        ];

        if ($controller_method == 'show') {
            $modelo['empleados_asignados'] = $this->empleados
                ->map(function ($e) {
                    $e['cargo'] = Cargo::where('id', $e['cargo_id'])->first()->nombre;
                    return $e;
                });
        }

        return $modelo;
    }
}
