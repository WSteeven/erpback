<?php

namespace App\Http\Resources;

use App\Models\Empleado;
use App\Models\EmpleadoSubtarea;
use App\Models\Grupo;
use App\Models\GrupoSubtarea;
use App\Models\Subtarea;
use App\Models\Tarea;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SubtareaResource extends JsonResource
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
        $tarea = $this->tarea;

        $modelo = [
            'id' => $this->id,
            'tarea' => $this->tarea->codigo_tarea,
            'tarea_id' => $this->tarea_id,
            'codigo_subtarea' => $this->tarea->tiene_subtareas ? $this->codigo_subtarea : null,
            'codigo_tarea_cliente' => $this->tarea->codigo_tarea_cliente,
            'titulo' => $this->titulo,
            'descripcion_completa' => $this->descripcion_completa,
            'observacion' => $this->observacion,
            'es_dependiente' => $this->es_dependiente,
            'subtarea_dependiente' => $this->subtareaDependiente?->codigo_subtarea,
            'fecha_solicitud' => $this->tarea->fecha_solicitud,
            'cliente' => $this->tarea->cliente?->empresa?->razon_social,
            'proyecto' => $this->tarea->proyecto?->codigo_proyecto,
            'cliente_final' => $tarea->clienteFinal?->id_cliente_final,
            'es_ventana' => $this->es_ventana,
            'fecha_inicio_trabajo' => Carbon::parse($this->fecha_inicio_trabajo)->format('d-m-Y'),
            'hora_inicio_trabajo' => $this->hora_inicio_trabajo,
            'hora_fin_trabajo' => $this->hora_fin_trabajo,
            'tipo_trabajo' => $this->tipo_trabajo?->descripcion,
            'fecha_hora_creacion' => $this->fecha_hora_creacion,
            'fecha_hora_agendado' => $this->fecha_hora_agendado,
            'fecha_hora_asignacion' => $this->fecha_hora_asignacion,
            'fecha_hora_ejecucion' => $this->fecha_hora_ejecucion,
            'fecha_hora_finalizacion' => $this->fecha_hora_finalizacion,
            'fecha_hora_realizado' => $this->fecha_hora_realizado,
            'fecha_hora_pendiente' => $this->fecha_hora_pendiente,
            'fecha_hora_suspendido' => $this->fecha_hora_suspendido,
            'motivo_suspendido' => $this->motivoSuspendido?->motivo,
            'fecha_hora_cancelado' => $this->fecha_hora_cancelado,
            'motivo_cancelado' => $this->motivoCancelado?->motivo,
            'modo_asignacion_trabajo' => $this->modo_asignacion_trabajo,

            'estado' => $this->estado,
            'dias_ocupados' => $this->fecha_hora_finalizacion ? Carbon::parse($this->fecha_hora_ejecucion)->diffInDays($this->fecha_hora_finalizacion) + 1 : null,
            'canton' => $this->obtenerCanton(),
            'es_responsable' => $this->verificarResponsable(),
            'empleado' => $this->extraerNombresApellidos($this->empleado),
            'fiscalizador' => $this->extraerNombresApellidos($this->tarea->fiscalizador),
            'coordinador' => $this->extraerNombresApellidos($this->tarea->coordinador),
            'grupo' => $this->grupo?->nombre,
            'tiene_subtareas' => $this->tarea->tiene_subtareas,
            // 'ejecutar_hoy' => $this->puedeEjecutarHoy(),
            'puede_ejecutar' => $this->verificarPuedeEjecutar(),
        ];

        if ($controller_method == 'show') {
            $modelo['cliente'] = $this->tarea->cliente_id;
            $modelo['tipo_trabajo'] = $this->tipo_trabajo_id;
            $modelo['proyecto'] = $this->proyecto_id;
            $modelo['tarea'] = $this->tarea_id;
            $modelo['coordinador'] = $this->tarea->coordinador_id;
            $modelo['fiscalizador'] = $this->tarea->fiscalizador_id;
            $modelo['cliente_final'] = $this->tarea->cliente_final_id;
            $modelo['subtarea_dependiente'] = $this->subtarea_dependiente_id;
            $modelo['empleado'] = $this->empleado_id;
            $modelo['grupo'] = $this->grupo_id;
        }

        return $modelo;
    }

    public function extraerNombres($listado)
    {
        $nombres = $listado->map(fn ($item) => $item->nombre)->toArray();
        return implode('; ', $nombres);
    }

    /* public function extraerNombresApellidos($listado)
    {
        $nombres = $listado->map(fn ($item) => $item->nombres . ' ' . $item->apellidos)->toArray();
        return implode('; ', $nombres);
    } */

    public function mapGrupoSeleccionado($gruposSeleccionados)
    {
        return $gruposSeleccionados->map(fn ($grupo) => [
            'id' => $grupo->grupo_id,
            'nombre' => Grupo::select('nombre')->where('id', $grupo->grupo_id)->first()->nombre,
            'responsable' => $grupo->responsable,
        ]);
    }

    /* private function listarEmpleados()
    {
        $empleadosSeleccionados = EmpleadoSubtarea::where('subtarea_id', $this->id)->orderBy('responsable', 'desc')->get();
        if ($empleadosSeleccionados) return $this->mapEmpleadoSeleccionado($empleadosSeleccionados);
    }*/

    public function mapEmpleadoSeleccionado($empleadosSubtarea)
    {
        return $empleadosSubtarea->map(function ($item) {
            $empleado = Empleado::find($item->empleado_id);
            return [
                'id' => $item->empleado_id,
                'nombres' => $empleado->nombres,
                'apellidos' => $empleado->apellidos,
                'telefono' => $empleado->telefono,
                'grupo' => $empleado->grupo?->nombre,
                'responsable' => $item->responsable,
                'roles' => implode(', ', $empleado->user->getRoleNames()->toArray()),
            ];
        });
    }

    public function verificarResponsable()
    {
        $usuario = User::find(Auth::id());

        if ($this->modo_asignacion_trabajo === Subtarea::POR_GRUPO) {
            $esLider = $usuario->hasRole(User::ROL_LIDER_DE_GRUPO);
            $grupo_id = $usuario->empleado->grupo_id;

            if ($esLider) {
                return $this->grupo_id == $grupo_id && $esLider;
            }
        }

        if ($this->modo_asignacion_trabajo === Subtarea::POR_EMPLEADO) {
            return $this->empleado_id == $usuario->empleado->id;
        }

        return false;
    }

    private function obtenerCanton()
    {
        if ($this->tarea->para_cliente_proyecto === Tarea::PARA_PROYECTO) {
            return $this->tarea->proyecto->canton->canton;
        } else if ($this->tarea->para_cliente_proyecto === Tarea::PARA_CLIENTE_FINAL) {
            return $this->tarea->clienteFinal->canton->canton;
        }
    }

    private function extraerNombresApellidos($empleado)
    {
        if (!$empleado) return null;
        return $empleado->nombres . ' ' . $empleado->apellidos;
    }

    private function verificarPuedeEjecutar()
    {
        $existeTrabajoEjecutadoHoy = !!$this->tarea->subtareas()->where('estado', Subtarea::EJECUTANDO)->fechaActual()->count();

        if ($this->hora_inicio_trabajo) return $this->puedeIniciarHoraTrabajo() && $this->puedeEjecutarHoy() && !$existeTrabajoEjecutadoHoy;
        else return $this->puedeEjecutarHoy() && !$existeTrabajoEjecutadoHoy;
    }

    private function puedeEjecutarHoy() {
        return $this->fecha_inicio_trabajo == Carbon::today()->toDateString();
    }

    private function puedeIniciarHoraTrabajo() {
        $horaInicio = Carbon::parse($this->hora_inicio_trabajo)->format('H:i:s');

        //return $horaInicio;// > Carbon::now(); //$this->hora_inicio_trabajo >= Str::substr(Carbon::now()->toTimeString(), 0, 5);
        return Carbon::now()->format('H:i:s') >= $horaInicio;
    }
}
