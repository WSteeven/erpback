<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\Shared\Utils;

class PermisoEmpleadoResource extends JsonResource
{
    //        private int $id_wellington =117;
//        private int $id_veronica_valencia=155;
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
//        $controller_method = $request->route()->getActionMethod();
        $jefe_id = $this->empleado?->jefe_id;
//        if($jefe_id== $this->id_wellington) $jefe_id = $this->id_veronica_valencia;
        return [
            'id' => $this->id,
            'tipo_permiso' => $this->tipo_permiso_id,
            'tipo_permiso_info' => $this->tipoPermiso != null ? $this->tipoPermiso->nombre : '',
            'fecha_hora_inicio' => $this->parsearFecha($this->fecha_hora_inicio),
            'fecha_hora_fin' => $this->parsearFecha($this->fecha_hora_fin),
            'fecha_recuperacion' => $this->parsearFecha($this->fecha_recuperacion, Utils::MASKFECHA),
            'hora_recuperacion' => $this->hora_recuperacion,
            'justificacion' => $this->justificacion,
            'estado' => $this->estado_permiso_id,
            'estado_permiso_info' => $this->estadoPermiso?->nombre,
            'empleado' => $this->empleado_id,
            'empleado_info' => $this->empleado?->nombres . ' ' . $this->empleado?->apellidos,
            'departamento' => $this->empleado?->departamento->nombre,
            'id_jefe_inmediato' => $jefe_id,
            'jefe_inmediato' => Empleado::extraerNombresApellidos(Empleado::find($jefe_id)),
            'fecha_hora_solicitud' => $this->parsearFecha($this->created_at),
            'fecha_hora_reagendamiento' => $this->fecha_hora_reagendamiento ? $this->parsearFecha($this->fecha_hora_reagendamiento) : null,
            'nombre' => $this->documento != null ? json_decode($this->documento)->nombre : '',
            'ruta' => $this->documento ? url(json_decode($this->documento)->ruta) : null,
            'tamanio_bytes' => $this->documento != null ? json_decode($this->documento)->tamanio_bytes : 0,
            'cargo_vacaciones' => $this->cargo_vacaciones,
            'suguiere_fecha' => !!$this->fecha_hora_reagendamiento,
            'aceptar_sugerencia' => $this->aceptar_sugerencia,
            'recupero' => $this->recupero,
            'cargo_descuento' => $this->bancoHoras->count()>0 ,
            'descontado' => $this->descontado,
            'observacion' => $this->observacion
        ];
    }

    private function parsearFecha($fecha, $mask = 'Y-m-d H:i:s')
    {
        return Carbon::parse($fecha)->format($mask);
    }
}
