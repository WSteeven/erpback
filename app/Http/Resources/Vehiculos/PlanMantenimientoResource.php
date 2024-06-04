<?php

namespace App\Http\Resources\Vehiculos;

use App\Models\Vehiculos\Vehiculo;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class PlanMantenimientoResource extends JsonResource
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
        // Log::channel('testing')->info('Log', ['resource', $this]);
        [$aplicar_desde, $estado, $listadoServicios] = Vehiculo::listadoItemsPlanMantenimiento($this->id, $controller_method);
        $modelo = [
            'id' => $this->id,
            'vehiculo' => $this->placa,
            'aplicar_desde' => count($this->itemsMantenimiento) > 0 ? $aplicar_desde : 'NO CONFIGURADO',
            'cantidad_servicios' => count($this->itemsMantenimiento),
            'datos_adicionales' => $this->datos_adicionales,
            'activo' => $estado,
        ];
        if ($controller_method == 'show') {
            $modelo['vehiculo']  = $this->id;
            $modelo['aplicar_desde']  = $aplicar_desde;
            $modelo['listadoServicios']  = $listadoServicios;
        }

        return $modelo;
    }
}
