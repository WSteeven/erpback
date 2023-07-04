<?php

namespace App\Http\Controllers;

use App\Models\Subtarea;
use App\Models\TipoTrabajo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteModuloTareaController extends Controller
{
    // Tipos de reportes
    const TRABAJOS_REALIZADOS = 'TRABAJOS REALIZADOS'; // Cuantificar trabajos realizados segun su tipo
    const TRABAJO_REALIZADO_POR_REGION = 'TRABAJO REALIZADO POR REGION'; // Cuantificar trabajo realizado segun su region
    const TRABAJO_REALIZADO_POR_REGION_TIPO_TRABAJO = 'TRABAJO REALIZADO POR REGION Y TIPO DE TRABAJO'; // Cuantificar trabajo realizado segun su region
    const TRABAJO_REALIZADO_POR_GRUPO_TIPO_TRABAJO = 'TRABAJO REALIZADO POR GRUPO Y TIPO DE TRABAJO'; // Cuantificar trabajo realizado segun su region
    const TRABAJO_REALIZADO_POR_GRUPO_TIPOS_TRABAJOS_EMERGENCIA = 'TRABAJO REALIZADO POR GRUPO Y TIPOS DE TRABAJOS EMERGENCIA'; // Cuantificar trabajo realizado segun su region

    public function index()
    {
        $tipoReporte = request('tipo_reporte');

        if ($tipoReporte === Self::TRABAJOS_REALIZADOS) $results = $this->trabajosRealizados();
        elseif ($tipoReporte === Self::TRABAJO_REALIZADO_POR_REGION) $results = $this->trabajoRealizadoPorRegion();
        elseif ($tipoReporte === Self::TRABAJO_REALIZADO_POR_REGION_TIPO_TRABAJO) $results = $this->trabajoRealizadoPorRegionTipoTrabajo();
        elseif ($tipoReporte === Self::TRABAJO_REALIZADO_POR_GRUPO_TIPO_TRABAJO) $results = $this->trabajoRealizadoPorGrupoTipoTrabajo();
        elseif ($tipoReporte === Self::TRABAJO_REALIZADO_POR_GRUPO_TIPOS_TRABAJOS_EMERGENCIA) $results = $this->trabajoRealizadoPorGrupoTiposTrabajos();

        return response()->json(compact('results'));
    }

    private function trabajosRealizados()
    {
        $idCliente = request('cliente_id');
        $mesAnio = request('mes_anio');

        $fecha = Carbon::createFromFormat('m-Y', $mesAnio)->startOfMonth();

        $idsTiposTrabajosCliente = TipoTrabajo::where('cliente_id', $idCliente)->pluck('id');

        $results = Subtarea::whereYear('fecha_hora_agendado', $fecha->year)
            ->whereMonth('fecha_hora_agendado', $fecha->month)
            ->whereIn('tipo_trabajo_id', $idsTiposTrabajosCliente)
            ->groupBy('tipo_trabajo_id')->select('tipo_trabajo_id', DB::raw('COUNT(*) AS suma_trabajo'))
            ->get();

        $results = $results->map(fn ($item) => [
            'tipo_trabajo' => $item->tipo_trabajo->descripcion,
            'suma_trabajo' => $item->suma_trabajo,
        ]);

        return $results;
    }

    private function trabajoRealizadoPorRegion()
    {
        $mesAnio = request('mes_anio');
        $fecha = Carbon::createFromFormat('m-Y', $mesAnio)->startOfMonth();

        // SELECT count(*) AS suma_trabajo, g.region FROM subtareas AS s INNER JOIN grupos as g ON s.grupo_id = g.id where YEAR(s.fecha_hora_agendado) = 2023 and MONTH(s.fecha_hora_agendado) = 07 GROUP BY g.region
        $results = DB::table('subtareas as s')
            ->join('grupos as g', 's.grupo_id', '=', 'g.id')
            ->select('g.region', DB::raw('count(*) as suma_trabajo'))
            ->whereYear('s.fecha_hora_agendado', '=', $fecha->year)
            ->whereMonth('s.fecha_hora_agendado', '=', $fecha->month)
            ->groupBy('g.region')
            ->get();

        return $results;
    }

    private function trabajoRealizadoPorRegionTipoTrabajo()
    {
        $idTipoTrabajo = request('tipo_trabajo_id');
        $mesAnio = request('mes_anio');
        $fecha = Carbon::createFromFormat('m-Y', $mesAnio)->startOfMonth();

        $results = DB::table('subtareas as s')
            ->join('grupos as g', 's.grupo_id', '=', 'g.id')
            ->select('g.region', DB::raw('count(*) as suma_trabajo'))
            ->whereYear('s.fecha_hora_agendado', '=', $fecha->year)
            ->whereMonth('s.fecha_hora_agendado', '=', $fecha->month)
            ->where('tipo_trabajo_id', $idTipoTrabajo)
            ->groupBy('g.region')
            ->get();

        return $results;
    }

    private function trabajoRealizadoPorGrupoTipoTrabajo()
    {
        $idTipoTrabajo = request('tipo_trabajo_id');
        $mesAnio = request('mes_anio');
        $fecha = Carbon::createFromFormat('m-Y', $mesAnio)->startOfMonth();

        $results = DB::table('subtareas as s')
            ->join('grupos as g', 's.grupo_id', '=', 'g.id')
            ->select('g.nombre as grupo', DB::raw('count(*) as suma_trabajo'))
            ->whereYear('s.fecha_hora_agendado', '=', $fecha->year)
            ->whereMonth('s.fecha_hora_agendado', '=', $fecha->month)
            ->where('tipo_trabajo_id', $idTipoTrabajo)
            ->groupBy('g.nombre')
            ->get();

        return $results;
    }

    // Emegerncias
    private function trabajoRealizadoPorGrupoTiposTrabajos()
    {
        $mesAnio = request('mes_anio');
        $fecha = Carbon::createFromFormat('m-Y', $mesAnio)->startOfMonth();

        $results = DB::table('subtareas as s')
            ->join('grupos as g', 's.grupo_id', '=', 'g.id')
            ->select(
                // DB::raw('count(*) as suma_trabajo'),
                'g.nombre as grupo',
                DB::raw('SUM(CASE WHEN s.tipo_trabajo_id = 1 THEN 1 ELSE 0 END) as corte_fibra'),
                DB::raw('SUM(CASE WHEN s.tipo_trabajo_id = 2 THEN 1 ELSE 0 END) as mantenimiento'),
                DB::raw('SUM(CASE WHEN s.tipo_trabajo_id = 3 THEN 1 ELSE 0 END) as soporte'),
                DB::raw('SUM(CASE WHEN s.tipo_trabajo_id = 7 THEN 1 ELSE 0 END) as tarea_programada'),
            )
            ->whereYear('s.fecha_hora_agendado', '=', $fecha->year)
            ->whereMonth('s.fecha_hora_agendado', '=', $fecha->month)
            ->groupBy('g.nombre')
            ->get();

        return $results;
    }
}
