<?php

namespace App\Http\Resources\Vehiculos;

use App\Http\Resources\ActividadRealizadaResource;
use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\Shared\Utils;

class BitacoraVehicularResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        // Log::channel('testing')->info('Log', ['Resource de bitacora...', $this]);
        $controller_method = $request->route()->getActionMethod();
        $modelo = [
            'id' => $this->id,
            'fecha' => date('Y-m-d', strtotime($this->fecha)),
            'hora_salida' => $this->hora_salida,
            'hora_llegada' => $this->hora_llegada,
            'km_inicial' => $this->km_inicial,
            'km_final' => $this->km_final,
            'km_recorridos' => $this->km_final ? $this->km_final - $this->km_inicial : 0,
            'tareas' =>  $this->tareas ? Tarea::whereIn('id', array_map('intval', Utils::convertirStringComasArray($this->tareas)))->pluck('codigo_tarea'): null,
            'tanque_inicio' => $this->tanque_inicio,
            'tanque_final' => $this->tanque_final,
            'fecha_finalizacion' => $this->fecha_finalizacion,
            'firmada' => $this->firmada,
            'chofer' => $this->chofer?->nombres . ' ' . $this->chofer?->apellidos,
            'chofer_id' => $this->chofer_id,
            'vehiculo' => $this->vehiculo->placa,
            'vehiculo_id' => $this->vehiculo_id,
        ];

        if ($controller_method == 'show' || $controller_method == 'ultima') {
            $modelo['imagen_inicial'] = $this->imagen_inicial ? url($this->imagen_inicial) : null;
            $modelo['tareas'] = $this->tareas ? array_map('intval', Utils::convertirStringComasArray($this->tareas)) : null;
            $modelo['tickets'] = $this->tickets ? array_map('intval', Utils::convertirStringComasArray($this->tickets)) : null;
            $modelo['actividadesRealizadas'] = ActividadRealizadaResource::collection($this->actividades);
            $modelo['checklistAccesoriosVehiculo'] = $this->checklistAccesoriosVehiculo;
            $modelo['checklistVehiculo'] = $this->checklistVehiculo;
            $modelo['checklistImagenVehiculo'] = new ChecklistImagenVehiculoResource($this->checklistImagenVehiculo);

            $modelo['vehiculo'] = $this->vehiculo_id;
        }

        return $modelo;
    }
}
