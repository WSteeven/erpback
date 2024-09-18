<?php

namespace App\Http\Controllers;

use App\Models\CausaIntervencion;
use App\Models\Subtarea;
use App\Models\TipoTrabajo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\App\Componentes\ChartJS\GraficoChartJS;

class ReporteModuloTareaController extends Controller
{
    // Tipos de reportes
    const TRABAJOS_REALIZADOS = 'TRABAJOS REALIZADOS'; // Cuantificar trabajos realizados segun su tipo
    const TRABAJO_REALIZADO_POR_REGION = 'TRABAJO REALIZADO POR REGION'; // Cuantificar trabajo realizado segun su region
    const TRABAJO_REALIZADO_POR_REGION_TIPO_TRABAJO = 'TRABAJO REALIZADO POR REGION Y TIPO DE TRABAJO'; // Cuantificar trabajo realizado segun su region
    const TRABAJO_REALIZADO_POR_GRUPO_TIPO_TRABAJO = 'TRABAJO REALIZADO POR GRUPO Y TIPO DE TRABAJO'; // Cuantificar trabajo realizado segun su region
    const TRABAJO_REALIZADO_POR_GRUPO_TIPOS_TRABAJOS_EMERGENCIA = 'TRABAJO REALIZADO POR GRUPO Y TIPOS DE TRABAJOS EMERGENCIA'; // Cuantificar trabajo realizado segun su region
    const TRABAJO_REALIZADO_POR_GRUPO_CAUSA_INTERVENCION = 'TRABAJO REALIZADO POR GRUPO Y CAUSA DE INTERVENCION'; // Cuantificar trabajo realizado segun su region

    public function index()
    {
        $tipoReporte = request('tipo_reporte');

        if ($tipoReporte === Self::TRABAJOS_REALIZADOS) $results = $this->trabajosRealizados();
        elseif ($tipoReporte === Self::TRABAJO_REALIZADO_POR_REGION) $results = $this->trabajoRealizadoPorRegion();
        elseif ($tipoReporte === Self::TRABAJO_REALIZADO_POR_REGION_TIPO_TRABAJO) $results = $this->trabajoRealizadoPorRegionTipoTrabajo();
        elseif ($tipoReporte === Self::TRABAJO_REALIZADO_POR_GRUPO_TIPO_TRABAJO) $results = $this->trabajoRealizadoPorGrupoTipoTrabajo();
        elseif ($tipoReporte === Self::TRABAJO_REALIZADO_POR_GRUPO_TIPOS_TRABAJOS_EMERGENCIA) $results = $this->trabajoRealizadoPorGrupoTiposTrabajos();
        elseif ($tipoReporte === Self::TRABAJO_REALIZADO_POR_GRUPO_CAUSA_INTERVENCION) $results = $this->trabajoRealizadoPorGrupoCausaIntervencion();

        return response()->json(compact('results'));
    }

    private function trabajosRealizados()
    {
        $idCliente = request('cliente_id');
        $mesAnio = request('mes_anio');

        $fecha = Carbon::createFromFormat('m-Y', $mesAnio)->startOfMonth();

        $idsTiposTrabajosCliente = TipoTrabajo::where('cliente_id', $idCliente)->pluck('id');

        $results = Subtarea::whereYear('fecha_hora_finalizacion', $fecha->year)
            ->whereMonth('fecha_hora_finalizacion', $fecha->month)
            ->whereIn('tipo_trabajo_id', $idsTiposTrabajosCliente)
            ->whereNotNull('grupo_id')
            ->groupBy('tipo_trabajo_id')->select('tipo_trabajo_id', DB::raw('COUNT(*) AS suma_trabajo'))
            ->get();

        // Log::channel('testing')->info('Log', ['Sql', $results->toSql()]);

        $results = $results->map(fn($item) => [
            'clave' => $item->tipo_trabajo->descripcion,
            'valor' => $item->suma_trabajo,
        ]);

        return GraficoChartJS::mapear($results->toArray(), 'Tipos de trabajos realizados' ?? '', 'Cantidad de subtareas');
    }

    private function trabajoRealizadoPorRegion()
    {
        $mesAnio = request('mes_anio');
        $cliente_id = request('cliente_id');
        $fecha = Carbon::createFromFormat('m-Y', $mesAnio)->startOfMonth();

        // SELECT count(*) AS suma_trabajo, g.region FROM subtareas AS s INNER JOIN grupos as g ON s.grupo_id = g.id where YEAR(s.fecha_hora_ejecucion) = 2023 and MONTH(s.fecha_hora_ejecucion) = 07 GROUP BY g.region
        $results = DB::table('subtareas as s')
            ->join('grupos as g', 's.grupo_id', '=', 'g.id')
            ->join('tareas as t', function ($join)  use ($cliente_id) {
                $join->on('s.tarea_id', '=', 't.id')
                    ->where('t.cliente_id', $cliente_id);
            })
            ->select('g.region as clave', DB::raw('count(*) as valor'))
            ->whereYear('s.fecha_hora_finalizacion', '=', $fecha->year)
            ->whereMonth('s.fecha_hora_finalizacion', '=', $fecha->month)
            ->whereNotNull('grupo_id')
            ->groupBy('g.region')
            ->get();

        return GraficoChartJS::mapear($results->toArray(), 'Trabajos realizados por región' ?? '', 'Cantidad de subtareas');
    }

    private function trabajoRealizadoPorRegionTipoTrabajo()
    {
        $idTipoTrabajo = request('tipo_trabajo_id');
        $cliente_id = request('cliente_id');
        $mesAnio = request('mes_anio');
        $fecha = Carbon::createFromFormat('m-Y', $mesAnio)->startOfMonth();

        $results = DB::table('subtareas as s')
            ->join('grupos as g', 's.grupo_id', '=', 'g.id')
            ->join('tareas as t', function ($join)  use ($cliente_id) {
                $join->on('s.tarea_id', '=', 't.id')
                    ->where('t.cliente_id', $cliente_id);
            })
            ->select('g.region as clave', DB::raw('count(*) as valor'))
            ->whereYear('s.fecha_hora_finalizacion', '=', $fecha->year)
            ->whereMonth('s.fecha_hora_finalizacion', '=', $fecha->month)
            ->where('tipo_trabajo_id', $idTipoTrabajo)
            ->whereNotNull('grupo_id')
            ->groupBy('g.region')
            ->get();

        return GraficoChartJS::mapear($results->toArray(), 'Trabajos realizados por región y tipo de trabajo (' . TipoTrabajo::find($idTipoTrabajo)->descripcion . ')', 'Cantidad de subtareas');
    }

    private function trabajoRealizadoPorGrupoTipoTrabajo()
    {
        $idTipoTrabajo = request('tipo_trabajo_id');
        $mesAnio = request('mes_anio');
        $fecha = Carbon::createFromFormat('m-Y', $mesAnio)->startOfMonth();

        $results = DB::table('subtareas as s')
            ->join('grupos as g', 's.grupo_id', '=', 'g.id')
            ->select('g.nombre as clave', DB::raw('count(*) as valor'))
            ->whereYear('s.fecha_hora_finalizacion', '=', $fecha->year)
            ->whereMonth('s.fecha_hora_finalizacion', '=', $fecha->month)
            ->where('tipo_trabajo_id', $idTipoTrabajo)
            ->groupBy('g.nombre')
            ->get();

        return GraficoChartJS::mapear($results->toArray(), 'Trabajos realizados por grupo y tipo de trabajo (' . TipoTrabajo::find($idTipoTrabajo)->descripcion . ')', 'Cantidad de subtareas');
    }

    // Emegerncias - Solo aplica a Nedetel
    private function trabajoRealizadoPorGrupoTiposTrabajos()
    {
        $mesAnio = request('mes_anio');
        $cliente_id = request('cliente_id');
        $fecha = Carbon::createFromFormat('m-Y', $mesAnio)->startOfMonth();

        $results = DB::table('subtareas as s')
            ->join('grupos as g', 's.grupo_id', '=', 'g.id')
            ->join('tareas as t', function ($join)  use ($cliente_id) {
                $join->on('s.tarea_id', '=', 't.id')
                    ->where('t.cliente_id', $cliente_id);
            })
            ->select(
                // DB::raw('count(*) as suma_trabajo'),
                'g.nombre as grupo',
                DB::raw('SUM(CASE WHEN s.tipo_trabajo_id = 50 THEN 1 ELSE 0 END) as corte_fibra'),
                DB::raw('SUM(CASE WHEN s.tipo_trabajo_id = 51 THEN 1 ELSE 0 END) as mantenimiento'),
                DB::raw('SUM(CASE WHEN s.tipo_trabajo_id = 52 THEN 1 ELSE 0 END) as soporte'),
                DB::raw('SUM(CASE WHEN s.tipo_trabajo_id = 53 THEN 1 ELSE 0 END) as tarea_programada'),
            )
            ->whereYear('s.fecha_hora_finalizacion', '=', $fecha->year)
            ->whereMonth('s.fecha_hora_finalizacion', '=', $fecha->month)
            ->whereNotNull('grupo_id')
            ->groupBy('g.nombre')
            ->get();

        return $results;
    }

    private function trabajoRealizadoPorGrupoCausaIntervencion()
    {
        $tipo_trabajo_id = request('tipo_trabajo_id');
        $graficos = collect([]);

        $mesAnio = request('mes_anio');
        $inicioDelMes = Carbon::createFromFormat('m-Y', $mesAnio)->startOfMonth();

        $results = Subtarea::join('grupos as g', 'grupo_id', '=', 'g.id')
            ->select('g.nombre as clave', DB::raw('count(*) as valor'), 'causa_intervencion_id')
            ->whereYear('fecha_hora_finalizacion', '=', $inicioDelMes->year)
            ->whereMonth('fecha_hora_finalizacion', '=', $inicioDelMes->month)
            ->whereHas('causaIntervencion', function ($q) use ($tipo_trabajo_id) {
                $q->where('tipo_trabajo_id', $tipo_trabajo_id);
            })
            ->groupBy('g.nombre')
            ->get();

        $results = $results->groupBy('causa_intervencion_id');

        foreach ($results as $key => $listado) {
            $graficos->push(GraficoChartJS::mapear($listado->toArray(), CausaIntervencion::find($key)?->nombre ?? '', 'Cantidad de subtareas'));
        }

        return $graficos;
    }
}
