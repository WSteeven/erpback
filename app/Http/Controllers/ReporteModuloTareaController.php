<?php

namespace App\Http\Controllers;

use App\Exports\Tareas\ReporteAsistenciaTecnicosPeruExport;
use App\Models\CausaIntervencion;
use App\Models\Subtarea;
use App\Models\TipoTrabajo;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Exception;
use Src\App\Componentes\ChartJS\GraficoChartJS;
use Src\App\SqlServerService;
use Src\Shared\Utils;
use Tests\Models\Car;

class ReporteModuloTareaController extends Controller
{
    // Tipos de reportes
    const TRABAJOS_REALIZADOS = 'TRABAJOS REALIZADOS'; // Cuantificar trabajos realizados segun su tipo
    const TRABAJO_REALIZADO_POR_REGION = 'TRABAJO REALIZADO POR REGION'; // Cuantificar trabajo realizado segun su region
    const TRABAJO_REALIZADO_POR_REGION_TIPO_TRABAJO = 'TRABAJO REALIZADO POR REGION Y TIPO DE TRABAJO'; // Cuantificar trabajo realizado segun su region
    const TRABAJO_REALIZADO_POR_GRUPO_TIPO_TRABAJO = 'TRABAJO REALIZADO POR GRUPO Y TIPO DE TRABAJO'; // Cuantificar trabajo realizado segun su region
    const TRABAJO_REALIZADO_POR_GRUPO_TIPOS_TRABAJOS_EMERGENCIA = 'TRABAJO REALIZADO POR GRUPO Y TIPOS DE TRABAJOS EMERGENCIA'; // Cuantificar trabajo realizado segun su region
    const TRABAJO_REALIZADO_POR_GRUPO_CAUSA_INTERVENCION = 'TRABAJO REALIZADO POR GRUPO Y CAUSA DE INTERVENCION'; // Cuantificar trabajo realizado segun su region

    private SqlServerService $sqlServerService;

    public function __construct()
    {
        $this->sqlServerService = new SqlServerService();
    }

    public function index()
    {
        $tipoReporte = request('tipo_reporte');
        $results = [];

        if ($tipoReporte === self::TRABAJOS_REALIZADOS) $results = $this->trabajosRealizados();
        elseif ($tipoReporte === self::TRABAJO_REALIZADO_POR_REGION) $results = $this->trabajoRealizadoPorRegion();
        elseif ($tipoReporte === self::TRABAJO_REALIZADO_POR_REGION_TIPO_TRABAJO) $results = $this->trabajoRealizadoPorRegionTipoTrabajo();
        elseif ($tipoReporte === self::TRABAJO_REALIZADO_POR_GRUPO_TIPO_TRABAJO) $results = $this->trabajoRealizadoPorGrupoTipoTrabajo();
        elseif ($tipoReporte === self::TRABAJO_REALIZADO_POR_GRUPO_TIPOS_TRABAJOS_EMERGENCIA) $results = $this->trabajoRealizadoPorGrupoTiposTrabajos();
        elseif ($tipoReporte === self::TRABAJO_REALIZADO_POR_GRUPO_CAUSA_INTERVENCION) $results = $this->trabajoRealizadoPorGrupoCausaIntervencion();

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
            ->groupBy('tipo_trabajo_id')
            ->select('tipo_trabajo_id', DB::raw('COUNT(*) AS suma_trabajo'))
            ->get();

        $metadata_ids = Subtarea::whereYear('fecha_hora_finalizacion', $fecha->year)
            ->whereMonth('fecha_hora_finalizacion', $fecha->month)
            ->whereIn('tipo_trabajo_id', $idsTiposTrabajosCliente)
            ->whereNotNull('grupo_id')
            ->select('subtareas.id')->pluck('id');

        $results = $results->map(fn($item) => [
            'clave' => $item->tipo_trabajo->descripcion,
            'valor' => $item->suma_trabajo,
        ]);

        $metadata = [
            'ids' => $metadata_ids,
            'campo' => 'tipo_trabajo',
        ];

        return GraficoChartJS::mapear($results->toArray(), 'Tipos de trabajos realizados' ?? '', 'Cantidad de subtareas', $metadata);
    }

    private function trabajoRealizadoPorRegion()
    {
        $mesAnio = request('mes_anio');
        $cliente_id = request('cliente_id');
        $fecha = Carbon::createFromFormat('m-Y', $mesAnio)->startOfMonth();

        // SELECT count(*) AS suma_trabajo, g.region FROM subtareas AS s INNER JOIN grupos as g ON s.grupo_id = g.id where YEAR(s.fecha_hora_ejecucion) = 2023 and MONTH(s.fecha_hora_ejecucion) = 07 GROUP BY g.region
        $results = DB::table('subtareas as s')
            ->join('grupos as g', 's.grupo_id', '=', 'g.id')
            ->join('tareas as t', function ($join) use ($cliente_id) {
                $join->on('s.tarea_id', '=', 't.id')
                    ->where('t.cliente_id', $cliente_id);
            })
            ->select('g.region as clave', DB::raw('count(*) as valor'))
            ->whereYear('s.fecha_hora_finalizacion', '=', $fecha->year)
            ->whereMonth('s.fecha_hora_finalizacion', '=', $fecha->month)
            ->whereNotNull('grupo_id')
            ->groupBy('g.region')
            ->get();

        $metadata_ids = DB::table('subtareas as s')
            ->join('tareas as t', function ($join) use ($cliente_id) {
                $join->on('s.tarea_id', '=', 't.id')
                    ->where('t.cliente_id', $cliente_id);
            })
            ->whereYear('s.fecha_hora_finalizacion', '=', $fecha->year)
            ->whereMonth('s.fecha_hora_finalizacion', '=', $fecha->month)
            ->whereNotNull('grupo_id')
            ->get('s.id')->pluck('id');

        $metadata = [
            'ids' => $metadata_ids,
            'campo' => 'region',
        ];

        return GraficoChartJS::mapear($results->toArray(), 'Trabajos realizados por región' ?? '', 'Cantidad de subtareas', $metadata);
    }

    private function trabajoRealizadoPorRegionTipoTrabajo()
    {
        $idTipoTrabajo = request('tipo_trabajo_id');
        $cliente_id = request('cliente_id');
        $mesAnio = request('mes_anio');
        $fecha = Carbon::createFromFormat('m-Y', $mesAnio)->startOfMonth();

        $results = DB::table('subtareas as s')
            ->join('grupos as g', 's.grupo_id', '=', 'g.id')
            ->join('tareas as t', function ($join) use ($cliente_id) {
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

        $metadata_ids = DB::table('subtareas as s')
            ->join('tareas as t', function ($join) use ($cliente_id) {
                $join->on('s.tarea_id', '=', 't.id')
                    ->where('t.cliente_id', $cliente_id);
            })
            ->whereYear('s.fecha_hora_finalizacion', '=', $fecha->year)
            ->whereMonth('s.fecha_hora_finalizacion', '=', $fecha->month)
            ->where('tipo_trabajo_id', $idTipoTrabajo)
            ->whereNotNull('grupo_id')
            ->get('s.id')->pluck('id');

        $metadata = [
            'ids' => $metadata_ids,
            'campo' => 'region',
        ];

        return GraficoChartJS::mapear($results->toArray(), 'Trabajos realizados por región y tipo de trabajo (' . TipoTrabajo::find($idTipoTrabajo)->descripcion . ')', 'Cantidad de subtareas', $metadata);
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

        $metadata_ids = DB::table('subtareas as s')
            ->whereYear('s.fecha_hora_finalizacion', '=', $fecha->year)
            ->whereMonth('s.fecha_hora_finalizacion', '=', $fecha->month)
            ->where('tipo_trabajo_id', $idTipoTrabajo)
            ->get('s.id')->pluck('id');

        $metadata = [
            'ids' => $metadata_ids,
            'campo' => 'grupo',
        ];

        return GraficoChartJS::mapear($results->toArray(), 'Trabajos realizados por grupo y tipo de trabajo (' . TipoTrabajo::find($idTipoTrabajo)->descripcion . ')', 'Cantidad de subtareas', $metadata);
    }

    // Emegerncias - Solo aplica a Nedetel
    private function trabajoRealizadoPorGrupoTiposTrabajos()
    {
        $mesAnio = request('mes_anio');
        $cliente_id = request('cliente_id');
        $fecha = Carbon::createFromFormat('m-Y', $mesAnio)->startOfMonth();

        return DB::table('subtareas as s')
            ->join('grupos as g', 's.grupo_id', '=', 'g.id')
            ->join('tareas as t', function ($join) use ($cliente_id) {
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
    }

    private function trabajoRealizadoPorGrupoCausaIntervencion()
    {
        $tipo_trabajo_id = request('tipo_trabajo_id');
        $graficos = collect();

        $mesAnio = request('mes_anio');
        $inicioDelMes = Carbon::createFromFormat('m-Y', $mesAnio)->startOfMonth();

        $results = Subtarea::join('grupos as g', 'grupo_id', '=', 'g.id')
            ->select('g.nombre as clave', DB::raw('count(*) as valor'), 'causa_intervencion_id', 'subtareas.id as id')
            ->whereYear('fecha_hora_finalizacion', '=', $inicioDelMes->year)
            ->whereMonth('fecha_hora_finalizacion', '=', $inicioDelMes->month)
            ->whereHas('causaIntervencion', function ($q) use ($tipo_trabajo_id) {
                $q->where('tipo_trabajo_id', $tipo_trabajo_id);
            })
            ->groupBy('g.nombre', 'causa_intervencion_id')
            ->get();

        $metadata_ids = Subtarea::join('grupos as g', 'grupo_id', '=', 'g.id')
            ->select('causa_intervencion_id', 'subtareas.id as id')
            ->whereYear('fecha_hora_finalizacion', '=', $inicioDelMes->year)
            ->whereMonth('fecha_hora_finalizacion', '=', $inicioDelMes->month)
            ->whereHas('causaIntervencion', function ($q) use ($tipo_trabajo_id) {
                $q->where('tipo_trabajo_id', $tipo_trabajo_id);
            })
            ->get();

        $results = $results->groupBy('causa_intervencion_id');

        foreach ($results as $key => $listado) {
            $graficos->push(GraficoChartJS::mapear($listado->toArray(), CausaIntervencion::find($key)?->nombre ?? '', 'Cantidad de subtareas', ['campo' => 'grupo', 'ids' => $metadata_ids->filter(fn($item) => $item->causa_intervencion_id == $key)->pluck('id')]));
        }

        return $graficos;
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function asistenciaTecnicosAppenate(Request $request)
    {
        $fecha_inicio = Carbon::parse($request->fecha_inicio)->startOfDay();
        $fecha_fin = Carbon::parse($request->fecha_fin)->addDay()->startOfDay();

//        Log::channel('testing')->info('Log', ['asistenciaTecnicosAppenate -> request' => $request->all()]);
        $results = $this->sqlServerService->obtenerRegistros($fecha_inicio, $fecha_fin);
//        Log::channel('testing')->info('Log', ['asistenciaTecnicosAppenate -> resultados crudos', $results]);
        $results = $this->agruparResultadosObtenidos($results);
//        Log::channel('testing')->info('Log', ['asistenciaTecnicosAppenate -> resultados', $results]);

        $resultadosMapeados = $this->mapearRegistrosAsistenciaTecnicos($results);
//        Log::channel('testing')->info('Log', ['asistenciaTecnicosAppenate -> mapeados', $resultadosMapeados]);

        $datos = $this->aplanarResultados($resultadosMapeados);
        $filename = 'Reporte Asistencia Tecnicos del ' . $fecha_inicio->format('Y-m-d') . ' al ' . $fecha_fin->format('Y-m-d');
        return Excel::download(new ReporteAsistenciaTecnicosPeruExport($datos, $filename), "$filename.xlsx");

//        return response()->json(compact('results'));
    }

    private function aplanarResultados($datos)
    {
        $results = [];
        foreach ($datos as $fecha => $empleados) {
            foreach ($empleados as $nombre => $registro) {
                $results[] = [
                    'fecha' => $fecha,
                    'empleado' => $nombre,
//                    'entrada' => $registro['entrada'],
//                    'almuerzo_inicio' => $registro['almuerzo_inicio'] ?? null,
//                    'almuerzo_fin' => $registro['almuerzo_fin'] ?? null,
                    'entrada' => isset($registro['entrada']) ? Carbon::createFromFormat('d/m/Y H:i', $registro['entrada'])->format('H:i') : null,
                    'almuerzo_inicio' => isset($registro['almuerzo_inicio']) ? Carbon::createFromFormat('d/m/Y H:i', $registro['almuerzo_inicio'])->format('H:i') : null,
                    'almuerzo_fin' => isset($registro['almuerzo_fin']) ? Carbon::createFromFormat('d/m/Y H:i', $registro['almuerzo_fin'])->format('H:i') : null,
                    'salida' => isset($registro['salida']) ? Carbon::createFromFormat('d/m/Y H:i', $registro['salida'])->format('H:i') : null,
                ];
            }
        }

        return $results;
    }

    private function agruparResultadosObtenidos(mixed $resultados)
    {
        $resultados = collect($resultados); // <-- IMPORTANTE

        $results = $resultados->groupBy(function ($item) {
            return Carbon::parse($item['FechaInicioTurno'])->format('Y-m-d');
        });
        $results = collect($results)->map(function ($itemsPorFecha) {
            return $itemsPorFecha->groupBy('NOC');
        });

        return $results;
    }

    private function mapearRegistrosAsistenciaTecnicos(mixed $datosAgrupados)
    {
        $resultados = [];
        $nombresTecnicos = [
            'LUIS ALBERTO PEÑA LOPEZ',
            'UBER PIZARRO LUNA',
            'DARLIN TOCTO',
            'ALFREDO CARREÑO',
            'ENRIQUE CHAVEZ',
            'JEFFERSON QUEVEDO',
            'MAICOL MORAN',
            'JOAO BELEVAN',
            'CARLOS RAMOS',
            'JULIO SALAZAR',
            'DAVID GONZALES'
        ];

        $normalizar = function ($cadena) {
            $cadena = mb_strtoupper($cadena);
            return Utils::quitarTildes($cadena);
        };

        // Normaliza los nombres una vez
        $nombresTecnicosNormalizados = array_map($normalizar, $nombresTecnicos);

        foreach ($datosAgrupados as $fecha => $grupoPorNOC) {
            foreach ($grupoPorNOC as $grupoNOC => $registros) {
//                Log::channel('testing')->info('Log', ['asistenciaTecnicosAppenate -> grupo desnormalizado', $grupoNOC]);
                $grupoNOCNormalizado = $normalizar($grupoNOC);
//                Log::channel('testing')->info('Log', ['asistenciaTecnicosAppenate -> grupo normalizado', $grupoNOCNormalizado]);
                foreach ($nombresTecnicosNormalizados as $index => $nombreNormalizado) {
                    if (str_contains($grupoNOCNormalizado, $nombreNormalizado)) {
                        $nombreOriginal = $nombresTecnicos[$index];

                        $hora_entrada = null;
                        $hora_almuerzo_inicio = null;
                        $hora_almuerzo_fin = null;
                        $hora_salida = null;
                        $row_id = null;

                        foreach ($registros as $i => $registro) {
                            $tipoMantenimiento = $registro['TipoMantenimiento'];
                            $tipoActividad = $registro['TipoActividad'];
                            $inicio = $registro['FHInicioMtto'];
                            $fin = $registro['FHFinMtto'];
                            $row_id = $registro['RowId'];

                            if ($tipoMantenimiento === 'Inicio/Fin de turno' && $tipoActividad === 'Ingreso') {
                                $hora_entrada = $inicio;
                            }

                            if ($tipoMantenimiento === 'Alimentacion' && $tipoActividad === 'Almuerzo') {
                                $hora_almuerzo_inicio = $inicio;
                                $hora_almuerzo_fin = isset($registros[$i + 1]) ? $registros[$i + 1]['FHInicioMtto'] : $fin;
                            }

                            if ($tipoMantenimiento === 'Inicio/Fin de turno' && $tipoActividad === 'Salida') {
                                $hora_salida = $fin;
                            }
                        }

                        $resultados[$fecha][$nombreOriginal] = [
                            'rowID' => $row_id,
                            'entrada' => $hora_entrada,
                            'almuerzo_inicio' => $hora_almuerzo_inicio,
                            'almuerzo_fin' => $hora_almuerzo_fin,
                            'salida' => $hora_salida,
                        ];
                    }
                }
            }
        }

        return $resultados;
    }


}
