<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermisoEmpleadoResource extends JsonResource
{
        private string $mask = 'Y-m-d H:i:s';
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
//        $controller_method = $request->route()->getActionMethod();
        return [
            'id' => $this->id,
            'tipo_permiso' => $this->tipo_permiso_id,
            'tipo_permiso_info' => $this->tipoPermiso != null ? $this->tipoPermiso->nombre : '',
            'fecha_hora_inicio' => $this->cambiarFechaHora($this->fecha_hora_inicio),
            'fecha_hora_fin' => $this->cambiarFechaHora($this->fecha_hora_fin),
            'justificacion' => $this->justificacion,
            'estado' => $this->estado_permiso_id,
            'estado_permiso_info' => $this->estadoPermiso?->nombre,
            'empleado' => $this->empleado_id,
            'empleado_info' => $this->empleado?->nombres . ' ' . $this->empleado?->apellidos,
            'departamento' => $this->empleado?->departamento->nombre,
            'id_jefe_inmediato' => $this->empleado?->jefe->id,
            'jefe_inmediato' => $this->empleado?->jefe->nombres . '' . $this->empleado?->jefe->apellidos,
            'fecha_hora_solicitud' => Carbon::parse($this->created_at)->format($this->mask),
            'fecha_hora_reagendamiento' => $this->fecha_hora_reagendamiento ? Carbon::parse($this->fecha_hora_reagendamiento)->format($this->mask) : null,
            'nombre' => $this->documento != null ? json_decode($this->documento)->nombre : '',
            'ruta' => $this->documento ? url(json_decode($this->documento)->ruta) : null,
            'tamanio_bytes' => $this->documento != null ? json_decode($this->documento)->tamanio_bytes : 0,
            'cargo_vacaciones' => $this->cargo_vacaciones,
            'suguiere_fecha' => !!$this->fecha_hora_reagendamiento,
            'aceptar_sugerencia' => $this->aceptar_sugerencia,
            'recupero' => $this->recupero,
            'observacion' => $this->observacion
        ];
    }

    private function cambiarFechaHora($fecha)
    {
        return Carbon::parse($fecha)->format($this->mask);
    }
}
