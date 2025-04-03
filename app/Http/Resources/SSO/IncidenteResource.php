<?php

namespace App\Http\Resources\SSO;

use App\Models\Empleado;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $es_de_inspeccion
 * @property mixed $empleadoReporta
 * @property mixed $inspeccion
 * @property mixed $empleadoInvolucrado
 */
class IncidenteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $controller_method = request()->route()->getActionMethod();

        $modelo = [
            'id' => $this['id'],
            'titulo' => $this['titulo'],
            'descripcion' => $this['descripcion'],
            'coordenadas' => $this['coordenadas'],
            'tipo_incidente' => $this['tipo_incidente'],
            'estado' => $this['estado'],
            'detalles_productos' => $this['detalles_productos'] ? json_decode($this['detalles_productos']) : [],
            'empleado_reporta' => Empleado::extraerNombresApellidos($this->empleadoReporta),
            'empleado_involucrado' => Empleado::extraerNombresApellidos($this->empleadoInvolucrado),
            'inspeccion' => $this->inspeccion?->titulo,
            'cliente' => $this['cliente_id'],
            'seguimiento_incidente_id' => $this->seguimientoIncidente?->id,
            'solicitud_descuento' => $this->solicitudDescuento?->id,
            'pedido' => $this->pedido?->id,
            'devolucion' => $this->devolucion?->id,
            'created_at' => Carbon::parse($this['created_at'])->format('Y-m-d H:i:s'),
            'acciones_correctivas' => $this->seguimientoIncidente?->acciones_correctivas,
        ];

        if ($controller_method == 'show') {
            $modelo['empleado_reporta'] = $this['empleado_reporta_id'];
            $modelo['empleado_involucrado'] = $this['empleado_involucrado_id'];
            $modelo['inspeccion'] = $this['inspeccion_id'];
        }

        return $modelo;
    }
}
