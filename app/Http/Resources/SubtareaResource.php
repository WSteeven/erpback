<?php

namespace App\Http\Resources;

use App\Models\Empleado;
use App\Models\EmpleadoSubtarea;
use App\Models\Grupo;
use App\Models\GrupoSubtarea;
use App\Models\MotivoSuspendido;
use App\Models\Subtarea;
use App\Models\Tarea;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $campos = $request->query('campos') ? explode(',', $request->query('campos')) : [];
        $controller_method = $request->route()->getActionMethod();
        $tarea = $this->tarea;
        $ultimaSuspension = DB::table('motivo_suspendido_subtarea')->where('subtarea_id', $this->id)->latest()->first(); // Obtener el último registro de la tabla pivote
        // Log::channel('testing')->info('Log', compact('tarea'));

        $modelo = [
            'id' => $this->cargar('id', $campos) ? $this->id : null,
            'codigo_tarea' => $this->cargar('codigo_tarea', $campos) || !count($campos) ? $this->tarea?->codigo_tarea : null,
            'tarea_id' => $this->cargar('tarea_id', $campos) ? $this->tarea_id : null,
            'codigo_subtarea' => $this->cargar('codigo_subtarea', $campos) ? $this->codigo_subtarea : null,
            'codigo_tarea_cliente' => $this->tarea?->codigo_tarea_cliente,
            'titulo' => $this->cargar('titulo', $campos) || !count($campos) ? $this->titulo : null,
            'descripcion_completa' => $this->cargar('descripcion_completa', $campos) || !count($campos) ? $this->descripcion_completa : null,
            'observacion' => $this->cargar('observacion', $campos) || !count($campos) ? $this->observacion : null,
            'es_dependiente' => $this->cargar('es_dependiente', $campos) || !count($campos) ? $this->es_dependiente : null,
            'subtarea_dependiente' => $this->cargar('subtarea_dependiente', $campos) || !count($campos) ? $this->subtareaDependiente?->codigo_subtarea : null,
            'subtarea_dependiente_id' => $this->cargar('subtarea_dependiente_id', $campos) ? $this->subtarea_dependiente_id : null,
            'fecha_solicitud' => $this->cargar('fecha_solicitud', $campos) ? $this->tarea?->fecha_solicitud : null,
            'cliente' => $this->cargar('cliente', $campos) ? $this->tarea->cliente?->empresa?->razon_social : null,
            'cliente_id' => $this->cargar('cliente_id', $campos) ? $this->tarea->cliente_id : null,
            'proyecto' => $this->cargar('proyecto', $campos) ? $this->tarea->proyecto?->codigo_proyecto : null,
            'ruta_tarea' => $this->cargar('ruta_tarea', $campos) ? $this->tarea->rutaTarea?->ruta : null,
            'cliente_final' => $this->cargar('cliente_final', $campos) ? $tarea->clienteFinal?->id_cliente_final : null,
            'es_ventana' => $this->cargar('es_ventana', $campos) ? $this->es_ventana : null,
            'fecha_inicio_trabajo' => $this->cargar('fecha_inicio_trabajo', $campos) ? Carbon::parse($this->fecha_inicio_trabajo)->format('d-m-Y') : null,
            'hora_inicio_trabajo' => $this->cargar('hora_inicio_trabajo', $campos) ? $this->hora_inicio_trabajo : null,
            'hora_fin_trabajo' => $this->cargar('hora_fin_trabajo', $campos) ? $this->hora_fin_trabajo : null,
            'tipo_trabajo' => $this->cargar('tipo_trabajo', $campos) ? $this->tipo_trabajo?->descripcion : null,
            'fecha_hora_creacion' => $this->cargar('fecha_hora_creacion', $campos) ? $this->formatTimestamp($this->fecha_hora_creacion) : null,
            'fecha_hora_agendado' => $this->cargar('fecha_hora_agendado', $campos) ? $this->formatTimestamp($this->fecha_hora_agendado) : null,
            'fecha_hora_asignacion' => $this->cargar('fecha_hora_asignacion', $campos) ? $this->formatTimestamp($this->fecha_hora_asignacion) : null,
            'fecha_hora_ejecucion' => $this->cargar('fecha_hora_ejecucion', $campos) ? $this->formatTimestamp($this->fecha_hora_ejecucion) : null,
            'fecha_hora_finalizacion' => $this->cargar('fecha_hora_finalizacion', $campos) ? $this->formatTimestamp($this->fecha_hora_finalizacion) : null,
            'fecha_hora_realizado' => $this->cargar('fecha_hora_realizado', $campos) ? $this->formatTimestamp($this->fecha_hora_realizado) : null,
            'fecha_hora_pendiente' => $this->cargar('fecha_hora_pendiente', $campos) ? $this->formatTimestamp($this->fecha_hora_pendiente) : null,
            'fecha_hora_suspendido' => $this->cargar('fecha_hora_suspendido', $campos) ? ($ultimaSuspension ? $this->formatTimestamp($ultimaSuspension->created_at) : null) : null,
            'motivo_suspendido' => $this->cargar('motivo_suspendido', $campos) ? ($ultimaSuspension ? MotivoSuspendido::find($ultimaSuspension->motivo_suspendido_id)->motivo : null) : null,
            'fecha_hora_cancelado' => $this->cargar('fecha_hora_cancelado', $campos) ? $this->formatTimestamp($this->fecha_hora_cancelado) : null,
            'motivo_cancelado' => $this->cargar('motivo_cancelado', $campos) ? $this->motivoCancelado?->motivo : null,
            'modo_asignacion_trabajo' => $this->cargar('modo_asignacion_trabajo', $campos) ? $this->modo_asignacion_trabajo : null,
            'empleados_designados' => $this->cargar('empleados_designados', $campos) ? $this->obtenerEmpleadosDesignados() : null,
            'estado' => $this->cargar('estado', $campos) ? $this->estado : null,
            'dias_ocupados' => $this->cargar('id', $campos) ? ($this->fecha_hora_finalizacion ? Carbon::parse($this->fecha_hora_ejecucion)->diffInDays($this->fecha_hora_finalizacion) + 1 : null) : null,
            'canton' => $this->cargar('canton', $campos) ? $this->obtenerCanton() : null,
            'es_responsable' => $this->cargar('es_responsable', $campos) ? $this->verificarSiEsResponsable() : null,
            'empleado_responsable_id' => $this->cargar('empleado_responsable_id', $campos) ? $this->empleado_id : null, // Se utiliza para que el coordinador pueda acceder a los materiales del empleado respondable ya sea individual o de grupo y poder manipular sus materiales al editar el seguimiento.
            'empleado_responsable' => $this->cargar('empleado_responsable', $campos) ? $this->extraerNombresApellidos($this->empleado) : null,
            'empleado' => $this->cargar('empleado', $campos) ? $this->extraerNombresApellidos($this->empleado) : null,
            'fiscalizador' => $this->cargar('fiscalizador', $campos) ? $this->extraerNombresApellidos($this->tarea->fiscalizador) : null,
            'coordinador' => $this->cargar('coordinador', $campos) ? $this->extraerNombresApellidos($this->tarea->coordinador) : null,
            'coordinador_id' => $this->cargar('coordinador_id', $campos) ? $this->tarea->coordinador_id : null,
            'grupo' => $this->cargar('grupo', $campos) ? $this->grupoResponsable?->nombre : null,
            'grupo_id' => $this->cargar('grupo_id', $campos) ? $this->obtenerGrupo() : null,
            'tiene_subtareas' => $this->cargar('tiene_subtareas', $campos) ? $tarea->tiene_subtareas : null,
            'causa_intervencion_id' => $this->cargar('empleado', $campos) ? $this->causa_intervencion_id : null,
            'puede_ejecutar' => $this->cargar('puede_ejecutar', $campos) ? $this->verificarSiPuedeEjecutar() : null,
            'puede_suspender' => $this->cargar('puede_suspender', $campos) ? $this->puedeEjecutarHoy() : null,
            'seguimiento' => $this->cargar('seguimiento', $campos) ? $this->seguimiento_id : null,
            'tiempo_estimado' => $this->cargar('tiempo_estimado', $campos) ? $this->tiempo_estimado : null,
            'cantidad_adjuntos' => $this->cargar('cantidad_adjuntos', $campos) ? $this->archivos?->count() : null,
            'metraje_tendido' => $this->cargar('metraje_tendido', $campos) ? $this->metraje_tendido : null,
            'etapa_id' => $tarea->etapa_id,
            'proyecto_id' => $this->cargar('proyecto_id', $campos) ? $tarea->proyecto_id : null, // $tarea->proyecto_id,
            'etapa' => $this->cargar('etapa', $campos) ? $this->tarea->etapa?->nombre : null,
            'valor_alimentacion' => $this->obtenerValorAlimentacion(),
        ];

        if ($controller_method == 'show') {
            $modelo['cliente'] = $this->tarea->cliente_id;
            $modelo['tipo_trabajo'] = $this->tipo_trabajo_id;
            $modelo['proyecto'] = $this->proyecto_id;
            $modelo['tarea'] = $this->tarea_id;
            $modelo['codigo_tarea'] = $this->tarea->codigo_tarea;
            $modelo['coordinador'] = $this->tarea->coordinador_id;
            $modelo['fiscalizador'] = $this->tarea->fiscalizador_id;
            $modelo['cliente_final'] = $this->tarea->cliente_final_id;
            $modelo['subtarea_dependiente'] = $this->subtarea_dependiente_id;
            $modelo['empleado'] = $this->empleado_id;
            $modelo['grupo'] = $this->grupo_id;
            $modelo['grupo_nombre'] = $this->grupoResponsable?->nombre;
            $modelo['causa_intervencion'] = $this->causa_intervencion_id;
        }

        $data = [];
        /*foreach ($campos as $campo) {
            if (isset($modelo[$campo])) {
                // $data[$campo] = $this->{$campo};
                $data[$campo] = $modelo[$campo];
            }
        }*/

        return $modelo;
        // return count($campos) ? $data : $modelo;
    }

    private function cargar($campo, $campos)
    {
        return in_array($campo, $campos) || !count($campos);
    }

    private function formatTimestamp($timestamp)
    {
        if ($timestamp) return Carbon::parse($timestamp)->format('d-m-Y H:i:s');
    }

    public function obtenerValorAlimentacion()
    {
        $alimentacion_grupo = $this->alimentacionGrupo;
        return $alimentacion_grupo->precio * $alimentacion_grupo->cantidad_personas;
    }

    public function extraerNombres($listado)
    {
        $nombres = $listado->map(fn($item) => $item->nombre)->toArray();
        return implode('; ', $nombres);
    }

    public function mapGrupoSeleccionado($gruposSeleccionados)
    {
        return $gruposSeleccionados->map(fn($grupo) => [
            'id' => $grupo->grupo_id,
            'nombre' => Grupo::select('nombre')->where('id', $grupo->grupo_id)->first()->nombre,
            'responsable' => $grupo->responsable,
        ]);
    }

    public function obtenerEmpleadosDesignados()
    {
        if ($this->empleados_designados) {
            $empleados = Empleado::whereIn('id', $this->empleados_designados)->get();
            return $this->mapEmpleadoSeleccionado($empleados);
        } else {
            $empleadosGrupo = Empleado::where('grupo_id', $this->grupo_id)->get();
            return $this->mapEmpleadoSeleccionado($empleadosGrupo);
        }
    }

    public function mapEmpleadoSeleccionado($empleadosSubtarea)
    {
        return $empleadosSubtarea->map(function ($empleado) {
            // Log::channel('testing')->info('Log', compact('empleado'));
            // $empleado = Empleado::find($item->empleado_id);
            return [
                'id' => $empleado->id,
                'nombres' => $empleado->nombres,
                'apellidos' => $empleado->apellidos,
                'telefono' => $empleado->telefono,
                'grupo' => $empleado->grupo?->nombre,
                'es_responsable' => $empleado->id == $this->empleado_id, //$empleado->pivot->es_responsable ? true : false,
                'cargo' => $empleado->cargo?->nombre,
                'roles' => implode(', ', $empleado->user->getRoleNames()->toArray()),
            ];
        });
    }

    // update subtareas set empleado_id = 47 where grupo_id = 18;

    public function verificarSiEsResponsableOld()
    {
        $usuario = Auth::user();

        if ($this->modo_asignacion_trabajo === Subtarea::POR_GRUPO) {
            $esLider = $usuario->hasRole(User::ROL_LIDER_DE_GRUPO);
            $grupo_id = $usuario->empleado->grupo_id;

            return $this->grupo_id == $grupo_id && $esLider;
        }

        if ($this->modo_asignacion_trabajo === Subtarea::POR_EMPLEADO) {
            return $this->empleado_id == $usuario->empleado->id;
        }

        return false;
    }

    public function verificarSiEsResponsable()
    {
        $usuario = Auth::user();

        /*if ($this->modo_asignacion_trabajo === Subtarea::POR_GRUPO) {
            // solucion temporal porque éste campo está vacio y no deberia de estarlo
            if (!$this->empleado_id) {
                $esLider = $usuario->hasRole(User::ROL_LIDER_DE_GRUPO);
                $grupo_id = $usuario->empleado->grupo_id;
                return $this->grupo_id == $grupo_id && $esLider;
            }

            return $this->empleado_id == $usuario->empleado->id;
        }

        if ($this->modo_asignacion_trabajo === Subtarea::POR_EMPLEADO) {
            return $this->empleado_id == $usuario->empleado->id;
        } */

        return $this->empleado_id == $usuario->empleado->id;
        // return false;
    }

    private function obtenerCanton()
    {
        if ($this->tarea->para_cliente_proyecto === Tarea::PARA_PROYECTO) {
            return $this->tarea->proyecto->canton?->canton;
        } else if ($this->tarea->para_cliente_proyecto === Tarea::PARA_CLIENTE_FINAL) {
            return $this->tarea->clienteFinal?->canton?->canton;
        }
    }

    private function obtenerGrupo()
    {
        return $this->grupo_id ?? $this->empleado->grupo_id;
    }

    private function extraerNombresApellidos($empleado)
    {
        if (!$empleado) return null;
        return $empleado->nombres . ' ' . $empleado->apellidos;
    }

    private function verificarSiPuedeEjecutar()
    {
        $existeTrabajoEjecutado = !!$this->empleado?->subtareas()->where('estado', Subtarea::EJECUTANDO)->count();
        return $this->puedeEjecutarHoy() && !$existeTrabajoEjecutado;
    }

    private function verificarSiPuedeEjecutarOld()
    {
        if ($this->modo_asignacion_trabajo === Subtarea::POR_GRUPO) {
            $existeTrabajoEjecutado = !!$this->grupoResponsable->subtareas()->where('estado', Subtarea::EJECUTANDO)->count();
            // $existeTrabajoEjecutado = !!$this->empleado->subtareas()->where('estado', Subtarea::EJECUTANDO)->count();
            // Log::channel('testing')->info('Log', compact('existeTrabajoEjecutado'));

            // if ($this->hora_inicio_trabajo) return $this->puedeEjecutarHoy() && $this->puedeIniciarHora() && $this->verificarSiEsResponsable() && !$existeTrabajoEjecutado;
            return $this->puedeEjecutarHoy() && !$existeTrabajoEjecutado; // $this->verificarSiEsResponsable() se quita para q pueda usar el coordinador desde el front se valida el resto
        }

        if ($this->modo_asignacion_trabajo === Subtarea::POR_EMPLEADO) {
            $existeTrabajoEjecutado = !!$this->empleado->subtareas()->where('estado', Subtarea::EJECUTANDO)->count();
            return $this->puedeEjecutarHoy() && !$existeTrabajoEjecutado; // $this->verificarSiEsResponsable() igual q arriba
        }
    }

    private function puedeEjecutarHoy()
    {
        return $this->fecha_inicio_trabajo <= Carbon::today()->toDateString();
    }

    private function puedeIniciarHora()
    {
        $horaInicio = Carbon::parse($this->hora_inicio_trabajo)->format('H:i:s');

        //return $horaInicio;// > Carbon::now(); //$this->hora_inicio_trabajo >= Str::substr(Carbon::now()->toTimeString(), 0, 5);
        return Carbon::now()->format('H:i:s') >= $horaInicio;
    }

    // borrar
    public function obtenerIdEmpleadoResponsable()
    {
        if ($this->modo_asignacion_trabajo === Subtarea::POR_GRUPO) {
            $empleados = Empleado::where('grupo_id', $this->grupo_id)->get();
            //$usuarioLider  = $empleados->filter(fn($empleado) => $empleado->user->hasRole(User::ROL_LIDER_DE_GRUPO));

            $liderIndex = $empleados->search(fn($empleado) => $empleado->user->hasRole(User::ROL_LIDER_DE_GRUPO));
            if ($liderIndex >= 0) return $empleados->get($liderIndex)->id;

            //if ($usuarioLider) return $usuarioLider[1]->id;
        }

        if ($this->modo_asignacion_trabajo === Subtarea::POR_EMPLEADO) {
            return $this->empleado_id;
        }

        return null;
    }
}
