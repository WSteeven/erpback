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
            'codigo_trabajo_padre' => $this->trabajo_padre_id,
            'codigo_trabajo' => $this->codigo_trabajo,
            'codigo_trabajo_cliente' => $this->codigo_trabajo_cliente,
            'titulo' => $this->titulo,
            'descripcion_completa' => $this->descripcion_completa,
            'observacion' => $this->observacion,
            'actividad_realizada' => $this->actividad_realizada,
            'es_dependiente' => $this->es_dependiente,
            'fiscalizador' => $this->fiscalizador,
            'trabajo_dependiente' => $this->trabajo_dependiente_id,
            'fecha_solicitud' => $this->fecha_solicitud,
            'para_cliente_proyecto' => $this->para_cliente_proyecto,
            'cliente' => $this->cliente?->empresa?->razon_social,
            'cliente_id' => $this->cliente_id,
            'proyecto' => $this->proyecto?->codigo_proyecto,
            //'subtarea_dependiente_id' => $this->subtarea_dependiente,
            'es_ventana' => $this->es_ventana,
            'fecha_agendado' => $this->fecha_agendado,
            'hora_inicio_agendado' => $this->hora_inicio_agendado,
            'hora_fin_agendado' => $this->hora_fin_agendado,
            'tipo_trabajo' => $this->tipo_trabajo?->descripcion,
            'tiene_subtrabajos' => $this->tiene_subtrabajos,
            //'tarea' => $this->tarea->codigo_tarea,
            //'tarea_id' => $this->tarea_id,
            'grupos' => $this->extraerNombres($this->grupos()->orderBy('responsable', 'desc')->get()),
            'empleados' => $this->extraerNombresApellidos($this->empleados()->orderBy('responsable', 'desc')->get()),
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
            //'cliente_final' => $this->tarea->cliente_final,
            'modo_asignacion_trabajo' => $this->modo_asignacion_trabajo,
            'estado' => $this->estado,
            //'responsable' => $this->verificarResponsable(), //!!$this->empleados()->wher    e('empleado_id', Auth::id())->where('responsable', true)->first(),
            'dias_ocupados' => $this->fecha_hora_finalizacion ? Carbon::parse($this->fecha_hora_ejecucion)->diffInDays($this->fecha_hora_finalizacion) + 1 : null,
            'canton' => $this->obtenerCanton(),
        ];

        if ($controller_method == 'show') {
            $modelo['cliente'] = $this->cliente_id;
            $modelo['tipo_trabajo'] = $this->tipo_trabajo_id;
            $modelo['proyecto'] = $this->proyecto_id;
            //$modelo['tarea'] = $this->tarea_id;
            $modelo['coordinador'] = $this->coordinador_id;
            $modelo['fiscalizador'] = $this->fiscalizador_id;
            $modelo['grupos_seleccionados'] = $this->mapGrupoSeleccionado(GrupoTrabajo::where('trabajo_id', $this->id)->orderBy('responsable', 'desc')->get());
            $modelo['empleados_seleccionados'] = $this->listarEmpleados();
            $modelo['cliente_final'] = $this->cliente_final_id;
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
            'responsable' => $grupo->responsable,
        ]);
    }

    private function listarEmpleados()
    {
        $empleadosSeleccionados = EmpleadoTrabajo::where('trabajo_id', $this->id)->orderBy('responsable', 'desc')->get();
        if ($empleadosSeleccionados) return $this->mapEmpleadoSeleccionado($empleadosSeleccionados);
    }

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
        $usuario = Auth::user();
        $esSecretario = $usuario->empleado->cargo === User::TECNICO_SECRETARIO;
        // $rolPermitido = in_array(User::ROL_TECNICO_SECRETARIO, $usuario->getRoleNames()->toArray());

        if ($this->modo_asignacion_trabajo === Trabajo::POR_GRUPO) {
            if ($esSecretario) {
                return !!$this->grupos()->where('grupo_id', $usuario->empleado->grupo_id)->where('responsable', true)->first();
            }
        }

        if ($this->modo_asignacion_trabajo === Trabajo::POR_EMPLEADO) {
            return !!$this->empleados()->where('empleado_id', Auth::id())->where('responsable', true)->first();
        }

        return false;
    }

    private function obtenerCanton()
    {
        if ($this->para_cliente_proyecto === Trabajo::PARA_PROYECTO) {
            return $this->proyecto->canton->canton;
        } else if ($this->para_cliente_proyecto === Trabajo::PARA_CLIENTE_FINAL) {
            return $this->clienteFinal->canton->canton;
        }
    }
}
