<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Resources\Json\JsonResource;

class MovilizacionSubtareaResource extends JsonResource
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
        $empleado = $this->empleado;

        $modelo = [
            'fecha_hora_salida' => Carbon::parse($this->fecha_hora_salida)->format('d-m-Y H:i:s'),
            'fecha_hora_llegada' => $this->fecha_hora_llegada ? Carbon::parse($this->fecha_hora_llegada)->format('d-m-Y H:i:s') : null,
            'tiempo_transcurrido' => $this->fecha_hora_llegada ? CarbonInterval::seconds(Carbon::parse($this->fecha_hora_llegada)->diffInSeconds(Carbon::parse($this->fecha_hora_salida)))->cascade()->forHumans() : null,
            'empleado' => $empleado->nombres . ' ' . $empleado->apellidos,
            'grupo' => $empleado->grupo->nombre,
            'subtarea' => $this->subtarea->codigo_subtarea,
            'motivo' => $this->motivo,
            'estado' => $this->fecha_hora_llegada ? 'RUTA COMPLETADA' : 'EN CAMINO'
        ];

        if ($controller_method == 'show') {
            $modelo['empleado'] = $this->empleado_id;
            $modelo['subtarea'] = $this->subtarea_id;
        }

        return $modelo;
    }
}
