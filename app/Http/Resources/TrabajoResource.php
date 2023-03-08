<?php

namespace App\Http\Resources;

use App\Models\Empleado;
use App\Models\EmpleadoTrabajo;
use App\Models\Grupo;
use App\Models\GrupoTrabajo;
use App\Models\Trabajo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TrabajoResource extends JsonResource
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
            'id' => $this->id,
            'tarea' => $this->tarea->codigo_tarea,
            'codigo_trabajo_padre' => $this->trabajo_padre_id,
            'codigo_trabajo' => $this->codigo_trabajo,
            'codigo_tarea_cliente' => $this->tarea->codigo_tarea_cliente,
            'titulo' => $this->titulo,
            'descripcion_completa' => $this->descripcion_completa,
            'observacion' => $this->observacion,
            'actividad_realizada' => $this->actividad_realizada,
            'es_dependiente' => $this->es_dependiente,
            'fiscalizador' => $this->fiscalizador,
            'trabajo_dependiente' => $this->trabajoDependiente?->codigo_trabajo,
            'fecha_solicitud' => $this->tarea->fecha_solicitud,
            'para_cliente_proyecto' => $this->para_cliente_proyecto,
            'cliente' => $this->tarea->cliente?->empresa?->razon_social,
            'cliente_id' => $this->cliente_id,
            'proyecto' => $this->tarea->proyecto?->codigo_proyecto,
            'es_ventana' => $this->es_ventana,
            'fecha_agendado' => $this->fecha_agendado,
            'hora_inicio_agendado' => $this->hora_inicio_agendado,
            'hora_fin_agendado' => $this->hora_fin_agendado,
            'tipo_trabajo' => $this->tipo_trabajo?->descripcion,
            'tiene_subtrabajos' => $this->tiene_subtrabajos,
            'grupos' => $this->extraerNombres($this->grupos()->orderBy('es_responsable', 'desc')->get()),
            'empleados' => $this->extraerNombresApellidos($this->empleados()->orderBy('es_responsable', 'desc')->get()),
            'coordinador' => $this->coordinador->nombres . ' ' . $this->coordinador->apellidos,
            'fecha_hora_creacion' => $this->fecha_hora_creacion,
            'fecha_hora_asignacion' => $this->fecha_hora_asignacion,
            'fecha_hora_ejecucion' => $this->fecha_hora_ejecucion,
            'fecha_hora_finalizacion' => $this->fecha_hora_finalizacion,
            'fecha_hora_realizado' => $this->fecha_hora_realizado,
            'fecha_hora_suspendido' => $this->fecha_hora_suspendido,
            'causa_suspencion' => $this->causa_suspencion,
            'fecha_hora_cancelacion' => $this->fecha_hora_cancelacion,
            'causa_cancelacion' => $this->causa_cancelacion,
            'modo_asignacion_trabajo' => $this->modo_asignacion_trabajo,

            'estado' => $this->estado,
            'dias_ocupados' => $this->fecha_hora_finalizacion ? Carbon::parse($this->fecha_hora_ejecucion)->diffInDays($this->fecha_hora_finalizacion) + 1 : null,
            'canton' => $this->obtenerCanton(),
            'es_responsable' => $this->verificarResponsable(), //!!$this->empleados()->where('empleado_id', Auth::id())->where('responsable', true)->first(),
        ];

        if ($controller_method == 'show') {
            $modelo['cliente'] = $this->cliente_id;
            $modelo['tipo_trabajo'] = $this->tipo_subtarea_id;
            $modelo['proyecto'] = $this->proyecto_id;
            $modelo['tarea'] = $this->tarea_id;
            $modelo['coordinador'] = $this->coordinador_id;
            $modelo['fiscalizador'] = $this->fiscalizador_id;
            $modelo['grupos_seleccionados'] = $this->mapGrupoSeleccionado(GrupoTrabajo::where('subtarea_id', $this->id)->orderBy('es_responsable', 'desc')->get());
            $modelo['empleados_seleccionados'] = $this->listarEmpleados();
            $modelo['cliente_final'] = $this->tarea->cliente_final_id;
            $modelo['trabajo_dependiente'] = $this->trabajo_dependiente_id;
        }

        return $modelo;
    }

    public function extraerNombres($listado)
    {
        $nombres = $listado->map(fn ($item) => $item->nombre)->toArray();
        return implode('; ', $nombres);
    }

    public function extraerNombresApellidos($listado)
    {
        $nombres = $listado->map(fn ($item) => $item->nombres . ' ' . $item->apellidos)->toArray();
        return implode('; ', $nombres);
    }

    public function mapGrupoSeleccionado($gruposSeleccionados)
    {
        return $gruposSeleccionados->map(fn ($grupo) => [
            'id' => $grupo->grupo_id,
            'nombre' => Grupo::select('nombre')->where('id', $grupo->grupo_id)->first()->nombre,
            'es_responsable' => $grupo->es_responsable,
        ]);
    }

    private function listarEmpleados()
    {
        $empleadosSeleccionados = EmpleadoTrabajo::where('subtarea_id', $this->id)->orderBy('es_responsable', 'desc')->get();
        if ($empleadosSeleccionados) return $this->mapEmpleadoSeleccionado($empleadosSeleccionados);
    }

    public function mapEmpleadoSeleccionado($empleadosTrabajo)
    {
        return $empleadosTrabajo->map(function ($item) {
            $empleado = Empleado::find($item->empleado_id);
            return [
                'id' => $item->empleado_id,
                'nombres' => $empleado->nombres,
                'apellidos' => $empleado->apellidos,
                'telefono' => $empleado->telefono,
                'grupo' => $empleado->grupo?->nombre,
                'es_responsable' => $item->es_responsable,
                'roles' => implode(', ', $empleado->user->getRoleNames()->toArray()),
            ];
        });
    }

    public function verificarResponsable()
    {
        $usuario = User::find(Auth::id());

        if ($this->modo_asignacion_trabajo === Trabajo::POR_GRUPO) {
            $esLider = $usuario->hasRole(User::ROL_TECNICO_LIDER_DE_GRUPO);
            $grupo_id = $usuario->empleado->grupo_id;

            if ($esLider) {
                return !!$this->grupos()->where('grupo_id', $grupo_id)->where('es_responsable', true)->first();
            }
        }

        if ($this->modo_asignacion_trabajo === Trabajo::POR_EMPLEADO) {
            return !!$this->empleados()->where('empleado_id', Auth::id())->where('es_responsable', true)->first();
        }

        return false;
    }

    private function obtenerCanton()
    {
        if ($this->tarea->para_cliente_proyecto === Trabajo::PARA_PROYECTO) {
            return $this->tarea->proyecto->canton->canton;
        } else if ($this->tarea->para_cliente_proyecto === Trabajo::PARA_CLIENTE_FINAL) {
            return $this->tarea->clienteFinal->canton->canton;
        }
    }
}
