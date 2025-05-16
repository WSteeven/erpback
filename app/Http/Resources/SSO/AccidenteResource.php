<?php

namespace App\Http\Resources\SSO;

use App\Models\Empleado;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $empleadoReporta
 */
class AccidenteResource extends JsonResource
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

        $modelo = [
            'id' => $this['id'],
            'titulo' => $this['titulo'],
            'descripcion' => $this['descripcion'],
            'medidas_preventivas' => $this['medidas_preventivas'],
            'empleados_involucrados' => json_decode($this['empleados_involucrados']),
            'fecha_hora_ocurrencia' => $this['fecha_hora_ocurrencia'],
            'coordenadas' => $this['coordenadas'],
            'consecuencias' => $this['consecuencias'],
            'estado' => $this['estado'],
            'lugar_accidente' => $this['lugar_accidente'],
            'empleado_reporta' => Empleado::extraerNombresApellidos($this->empleadoReporta),
            'seguimiento_accidente' => $this->seguimientoAccidente?->id,
            'created_at' => Carbon::parse($this['created_at'])->format('d-m-Y H:i:s'),
        ];

        /*if ($controller_method == 'show') {
            $modelo['empleado_reporta'] = $this['empleado_reporta_id'];
        }*/

        return $modelo;
    }
}
