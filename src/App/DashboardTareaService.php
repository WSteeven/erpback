<?php

namespace Src\App;

use Src\App\Componentes\ChartJS\GraficoChartJS;
use App\Http\Resources\SubtareaResource;
use App\Models\Empleado;
use App\Models\Subtarea;
use App\Models\Tarea;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class DashboardTareaService
{
    public function obtenerCantidadTareasActivas(Empleado $coordinador)
    {
        return $coordinador->tareasCoordinador()->where('finalizado', 0)->fechaInicioFin()->count();
    }

    public function obtenerCantidadTareasFinalizadas(Empleado $coordinador)
    {
        return $coordinador->tareasCoordinador()->where('finalizado', 1)->fechaInicioFin()->count();
    }

    public function obtenerSubtareasFinalizadas(Empleado $coordinador)
    {
        $tareas_id = $coordinador->tareasCoordinador()->where('finalizado', 1)->fechaInicioFin()->pluck('id');

        $subtareas = Subtarea::select('id', 'codigo_subtarea', 'fecha_hora_ejecucion', 'fecha_hora_realizado', 'tarea_id', 'empleado_id', 'estado')->whereIn('tarea_id', $tareas_id)->get();

        $subtareas = $subtareas->map(fn ($item) => [
            'id' => $item->id,
            'tarea_id' => $item->tarea_id,
            'empleado_responsable_id' => $item->empleado_id,
            'estado' => $item->estado,
            'codigo_subtarea' => $item->codigo_subtarea,
            'tiempo' => Carbon::parse($item->fecha_hora_realizado)->diffInHours(Carbon::parse($item->fecha_hora_ejecucion)),
        ]);

        return $subtareas;
    }

    public function obtenerSubtareasRealizadas(Empleado $coordinador)
    {
        $tareas_id = $coordinador->tareasCoordinador()->where('finalizado', 1)->fechaInicioFin()->pluck('id');

        $subtareas = Subtarea::select('id', 'codigo_subtarea', 'fecha_hora_ejecucion', 'fecha_hora_realizado', 'tarea_id', 'empleado_id', 'estado')->whereIn('tarea_id', $tareas_id)->get();

        $subtareas = $subtareas->map(fn ($item) => [
            'id' => $item->id,
            'tarea_id' => $item->tarea_id,
            'empleado_responsable_id' => $item->empleado_id,
            'estado' => $item->estado,
            'codigo_subtarea' => $item->codigo_subtarea,
            'tiempo' => Carbon::parse($item->fecha_hora_finalizacion)->diffInHours(Carbon::parse($item->fecha_hora_realizado)),
        ]);

        return $subtareas;
    }

    public function contarCantidadSubtareasPorEstado($subtareasFechaInicioFin, $estadoSubtarea)
    {
        return $subtareasFechaInicioFin->filter(fn ($subtarea) => $subtarea === $estadoSubtarea)->count();
    }

    public function obtenerSubtareasFechaInicioFin(Empleado $coordinador, $campos)
    {
        $fechaInicio = request('fecha_inicio');
        $fechaFin = request('fecha_fin');

        $fechaInicio = Carbon::createFromFormat('d-m-Y', $fechaInicio)->format('Y-m-d');
        $fechaFin = Carbon::createFromFormat('d-m-Y', $fechaFin)->addDay()->toDateString();

        //$camposConsultar = array_diff($campos, Subtarea::$noFiltrar);

        return Subtarea::whereIn('tarea_id', function ($query) use ($fechaInicio, $fechaFin, $coordinador) {
            $query->select('id')
                ->from('tareas')
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('coordinador_id', $coordinador->id);
        })->get();
    }

    public function obtenerSubtareasFechaInicioFinGrupo($idsGrupos, $idCoordinador)
    {
        $fechaInicio = request('fecha_inicio');
        $fechaFin = request('fecha_fin');

        $fechaInicio = Carbon::createFromFormat('d-m-Y', $fechaInicio)->format('Y-m-d');
        $fechaFin = Carbon::createFromFormat('d-m-Y', $fechaFin)->addDay()->toDateString();

        return Subtarea::whereIn('grupo_id', $idsGrupos)->whereIn('tarea_id', function ($query) use ($fechaInicio, $fechaFin, $idCoordinador) {
            $query->select('id')
                ->from('tareas')
                ->where('coordinador_id', $idCoordinador)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin]);
        })->get();
    }

    public function obtenerSubtareasFechaInicioFinEmpleado($idsEmpleados, $idCoordinador)
    {
        $fechaInicio = request('fecha_inicio');
        $fechaFin = request('fecha_fin');

        $fechaInicio = Carbon::createFromFormat('d-m-Y', $fechaInicio)->format('Y-m-d');
        $fechaFin = Carbon::createFromFormat('d-m-Y', $fechaFin)->addDay()->toDateString();

        return Subtarea::whereIn('empleado_id', $idsEmpleados)->whereIn('tarea_id', function ($query) use ($fechaInicio, $fechaFin, $idCoordinador) {
            $query->select('id')
                ->from('tareas')
                ->where('coordinador_id', $idCoordinador)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin]);
        })->get();
    }

    public function obtenerIdsGruposCoordinador(int $idCoordinador)
    {
        return DB::table('subtareas')
            ->join('tareas', 'tareas.id', '=', 'subtareas.tarea_id')
            ->where('tareas.coordinador_id', $idCoordinador)
            ->groupBy('subtareas.grupo_id')
            ->select('subtareas.grupo_id')
            ->pluck('grupo_id');
    }

    public function obtenerIdsEmpleadosCoordinador(int $idCoordinador)
    {
        return DB::table('subtareas')
            ->join('tareas', 'tareas.id', '=', 'subtareas.tarea_id')
            ->where('tareas.coordinador_id', $idCoordinador)
            ->groupBy('subtareas.empleado_id')
            ->select('subtareas.empleado_id')
            ->pluck('empleado_id');
    }

    public function generarListadoCantidadesPorEstadosSubtareas(
        $cantidadSubtareasAgendadas,
        $cantidadSubtareasEjecutadas,
        $cantidadSubtareasPausadas,
        $cantidadSubtareasSuspendidas,
        $cantidadSubtareasCanceladas,
        $cantidadSubtareasRealizadas,
        $cantidadSubtareasFinalizadas
    ) {
        return [
            [
                'estado' => Subtarea::AGENDADO,
                'total_subtareas' => $cantidadSubtareasAgendadas
            ],
            [
                'estado' => Subtarea::EJECUTANDO,
                'total_subtareas' => $cantidadSubtareasEjecutadas
            ],
            [
                'estado' => Subtarea::PAUSADO,
                'total_subtareas' => $cantidadSubtareasPausadas
            ],
            [
                'estado' => Subtarea::SUSPENDIDO,
                'total_subtareas' => $cantidadSubtareasSuspendidas
            ],
            [
                'estado' => Subtarea::CANCELADO,
                'total_subtareas' => $cantidadSubtareasCanceladas
            ],
            [
                'estado' => Subtarea::REALIZADO,
                'total_subtareas' => $cantidadSubtareasRealizadas
            ],
            [
                'estado' => Subtarea::FINALIZADO,
                'total_subtareas' => $cantidadSubtareasFinalizadas
            ],
        ];
    }

    public function consultarEmpleado()
    {
        $idCoordinador = request('empleado_id');
        $campos = request('campos') ? explode(',', request('campos')) : '*';

        // Busqueda del coordinador
        $coordinador = Empleado::find($idCoordinador);

        $cantidadTareasActivas = $this->obtenerCantidadTareasActivas($coordinador);
        $cantidadTareasFinalizadas = $this->obtenerCantidadTareasFinalizadas($coordinador);

        $subtareasCoordinador = $this->obtenerSubtareasFechaInicioFin($coordinador, $campos);
        $subtareasFechaInicioFin = $subtareasCoordinador->pluck('estado');

        $cantidadSubtareasAgendadas = $this->contarCantidadSubtareasPorEstado($subtareasFechaInicioFin, Subtarea::AGENDADO);
        $cantidadSubtareasEjecutadas = $this->contarCantidadSubtareasPorEstado($subtareasFechaInicioFin, Subtarea::EJECUTANDO);
        $cantidadSubtareasPausadas = $this->contarCantidadSubtareasPorEstado($subtareasFechaInicioFin, Subtarea::PAUSADO);
        $cantidadSubtareasSuspendidas = $this->contarCantidadSubtareasPorEstado($subtareasFechaInicioFin, Subtarea::SUSPENDIDO);
        $cantidadSubtareasCanceladas = $this->contarCantidadSubtareasPorEstado($subtareasFechaInicioFin, Subtarea::CANCELADO);
        $cantidadSubtareasRealizadas = $this->contarCantidadSubtareasPorEstado($subtareasFechaInicioFin, Subtarea::REALIZADO);
        $cantidadSubtareasFinalizadas = $this->contarCantidadSubtareasPorEstado($subtareasFechaInicioFin, Subtarea::FINALIZADO);

        // Linea tiempo
        $lineaTiempoSubtareasFinalizadasCoordinador = $this->obtenerSubtareasFinalizadas($coordinador);
        $lineaTiempoSubtareasRealizadasCoordinador = $this->obtenerSubtareasRealizadas($coordinador);

        /********
         Listados
         *********/
        $cantidadesPorEstadosSubtareas = $this->generarListadoCantidadesPorEstadosSubtareas(
            $cantidadSubtareasAgendadas,
            $cantidadSubtareasEjecutadas,
            $cantidadSubtareasPausadas,
            $cantidadSubtareasSuspendidas,
            $cantidadSubtareasCanceladas,
            $cantidadSubtareasRealizadas,
            $cantidadSubtareasFinalizadas
        );

        $subtareasCoordinador = SubtareaResource::collection($subtareasCoordinador);
        $idsGruposCoordinador = $this->obtenerIdsGruposCoordinador($idCoordinador);
        $subtareasGrupo = SubtareaResource::collection($this->obtenerSubtareasFechaInicioFinGrupo($idsGruposCoordinador, $idCoordinador));
        $idsEmpleadosCoordinador = $this->obtenerIdsEmpleadosCoordinador($idCoordinador);
        $subtareasEmpleado = SubtareaResource::collection($this->obtenerSubtareasFechaInicioFinEmpleado($idsEmpleadosCoordinador, $idCoordinador));

        /**********
        Respuesta
         ***********/
        $results = compact(
            'cantidadTareasActivas',
            'cantidadTareasFinalizadas',
            'cantidadSubtareasAgendadas',
            'cantidadSubtareasEjecutadas',
            'cantidadSubtareasPausadas',
            'cantidadSubtareasSuspendidas',
            'cantidadSubtareasCanceladas',
            'cantidadSubtareasRealizadas',
            'cantidadSubtareasFinalizadas',
            'subtareasCoordinador',
            'subtareasEmpleado',
            'subtareasGrupo',
            'cantidadesPorEstadosSubtareas',
            // Linea tiempo
            'lineaTiempoSubtareasFinalizadasCoordinador',
            'lineaTiempoSubtareasRealizadasCoordinador',
        );

        return response()->json(compact('results'));
    }

    public function consultarGrupo()
    {
        $fechaInicio = Carbon::createFromFormat('d-m-Y', request('fecha_inicio'))->format('Y-m-d');
        $fechaFin = Carbon::createFromFormat('d-m-Y', request('fecha_fin'))->addDay()->toDateString();
        $idGrupo = request('grupo_id');

        $subtareasConsultadas = Subtarea::where('grupo_id', $idGrupo)->whereIn('tarea_id', function ($query) use ($fechaInicio, $fechaFin) {
            $query->select('id')
                ->from('tareas')
                ->whereBetween('created_at', [$fechaInicio, $fechaFin]);
        })->get();

        $subtareas = SubtareaResource::collection($subtareasConsultadas);

        $estadosGrupo = $this->contarEstadosGrupo($subtareasConsultadas->toArray());
        $graficoEstadosGrupo = GraficoChartJS::mapear($estadosGrupo, 'Estados del grupo', 'Cantidad de subtareas');

        $results = compact(
            'subtareas',
            'graficoEstadosGrupo',
        );

        return compact('results');
    }

    function contarEstadosGrupo($result)
    {
        $conteo = array_reduce($result, function ($acumulador, $subtarea) {
            $estado = $subtarea['estado'];

            $elementoExistente = array_filter($acumulador, function ($item) use ($estado) {
                return $item['clave'] === $estado;
            });

            if (empty($elementoExistente)) {
                $acumulador[] = ['clave' => $estado, 'valor' => 1];
            } else {
                $clave = key($elementoExistente); // Obtener la clave del primer elemento
                $acumulador[$clave]['valor'] = $acumulador[$clave]['valor'] + 1;
            }

            return $acumulador;
        }, []);

        return $conteo;
    }
}
