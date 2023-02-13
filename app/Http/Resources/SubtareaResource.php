<?php

namespace App\Http\Resources;

use App\Models\Empleado;
use App\Models\EmpleadoSubtarea;
use App\Models\Grupo;
use App\Models\GrupoSubtarea;
use App\Models\Subtarea;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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

        $modelo = [
            'id' => $this->id,
            'codigo_subtarea' => $this->codigo_subtarea,
            'detalle' => $this->detalle,
            'descripcion_completa' => $this->descripcion_completa,
            'actividad_realizada' => $this->actividad_realizada,
            'es_dependiente' => $this->es_dependiente,
            'fiscalizador' => $this->fiscalizador,
            'subtarea_dependiente' => $this->subtarea?->codigo_subtarea,
            'subtarea_dependiente_id' => $this->subtarea_dependiente,
            'tipo_instalacion' => $this->tipo_instalacion,
            'id_servicio' => $this->id_servicio,
            'es_ventana' => $this->es_ventana,
            'fecha_ventana' => $this->fecha_ventana,
            'hora_inicio_ventana' => $this->hora_inicio_ventana,
            'hora_fin_ventana' => $this->hora_fin_ventana,
            'tipo_trabajo' => $this->tipo_trabajo->descripcion,
            'tarea' => $this->tarea->codigo_tarea,
            'tarea_id' => $this->tarea_id,
            'grupos' => $this->extraerNombres($this->grupos()->orderBy('responsable', 'desc')->get()),
            'empleados' => $this->extraerNombresApellidos($this->empleados()->orderBy('responsable', 'desc')->get()),
            'coordinador' => $this->tarea->coordinador->nombres . ' ' . $this->tarea->coordinador->apellidos,
            'fecha_hora_creacion' => $this->fecha_hora_creacion,
            'fecha_hora_asignacion' => $this->fecha_hora_asignacion,
            'fecha_hora_ejecucion' => $this->fecha_hora_ejecucion,
            'fecha_hora_finalizacion' => $this->fecha_hora_finalizacion,
            'fecha_hora_realizado' => $this->fecha_hora_realizado,
            'fecha_hora_suspendido' => $this->fecha_hora_suspendido,
            'causa_suspencion' => $this->causa_suspencion,
            'fecha_hora_cancelacion' => $this->fecha_hora_cancelacion,
            'causa_cancelacion' => $this->causa_cancelacion,
            'cliente_final' => $this->tarea->cliente_final,
            'modo_asignacion_trabajo' => $this->modo_asignacion_trabajo,
            'estado' => $this->estado,
            'responsable' => $this->verificarResponsable(), //!!$this->empleados()->where('empleado_id', Auth::id())->where('responsable', true)->first(),
            'dias_ocupados' => $this->fecha_hora_finalizacion ? Carbon::parse($this->fecha_hora_ejecucion)->diffInDays($this->fecha_hora_finalizacion) + 1 : null,
        ];

        if ($controller_method == 'show') {
            $modelo['cliente'] = $this->tarea->cliente_id;
            $modelo['tipo_trabajo'] = $this->tipo_trabajo_id;
            $modelo['tarea'] = $this->tarea_id;
            $modelo['grupos_seleccionados'] = $this->mapGrupoSeleccionado(GrupoSubtarea::where('subtarea_id', $this->id)->orderBy('responsable', 'desc')->get());
            $modelo['empleados_seleccionados'] = $this->listarEmpleados();
            $modelo['cliente_final'] = $this->tarea->cliente_final_id;
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
        $empleadosSeleccionados = EmpleadoSubtarea::where('subtarea_id', $this->id)->orderBy('responsable', 'desc')->get();
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
        $rolPermitido = in_array(User::ROL_TECNICO_SECRETARIO, $usuario->getRoleNames()->toArray());

        if ($this->modo_asignacion_trabajo === Subtarea::POR_GRUPO) {
            if ($rolPermitido) {
                return !!$this->grupos()->where('grupo_id', $usuario->empleado->grupo_id)->where('responsable', true)->first();
            }
        }

        if ($this->modo_asignacion_trabajo === Subtarea::POR_EMPLEADO) {
            return !!$this->empleados()->where('empleado_id', Auth::id())->where('responsable', true)->first();
        }

        return false;
    }
}
