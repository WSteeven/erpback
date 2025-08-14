<?php

namespace Src\App\RecursosHumanos\ControlPersonal;


use App\Models\ControlPersonal\Atraso;
use App\Models\Empleado;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Src\Shared\Charts\GraficoEChart;
use Src\Shared\Charts\LabelOption;
use Src\Shared\Charts\Series;

class DashboardControlPersonalService
{

    public function obtenerDashboard(Request $request)
    {
        $fecha_inicio = $request->fecha_inicio ? Carbon::parse($request->fecha_inicio)->startOfDay() : Carbon::now();
        $fecha_fin = $request->fecha_fin ? Carbon::parse($request->fecha_fin)->endOfDay() : Carbon::now();
        [$grafico, $results] = match ($request->tipo) {
            'ATRASOS' => $this->obtenerDashboardAtrasos($fecha_inicio, $fecha_fin),
            default => $this->obtenerDashboardAsistencias($fecha_inicio, $fecha_fin) //ASISTENCIAS,
        };

        return [$grafico, $results];
    }


    /**
     * La función `obtenerDashboardAtrasos` genera un resumen estadístico de los atrasos de empleados en un rango de fechas. Agrupa los registros por empleado, calcula totales y clasificaciones de atrasos (justificados/injustificados), suma en segundos, minutos y horas, identifica el momento de mayor ocurrencia, y prepara los datos para visualizarlos en un gráfico tipo barra usando la clase `GraficoEChart`. Retorna el gráfico y el arreglo de empleados con sus métricas. Utiliza modelos Eloquent y Carbon para manipulación de datos y fechas.
     * @param $fecha_inicio
     * @param $fecha_fin
     * @return array
     */
    public function obtenerDashboardAtrasos(Carbon $fecha_inicio,Carbon $fecha_fin)
    {
        $atrasos = Atraso::whereBetween('fecha_atraso', [$fecha_inicio, $fecha_fin])->orderBy('fecha_atraso', 'desc')->get();
        $atrasosAgrupadosPorEmpleado = $atrasos->groupBy('empleado_id');
        $empleadosAtrasados = [];
        // Implementar la logica para obtener los atrasos
        Log::channel('testing')->info('Log', ['obtenerDashboardAtrasos', $atrasos->groupBy('empleado_id')]);
        foreach ($atrasosAgrupadosPorEmpleado as $index => $atrasosPorEmpleado) {
            $row['empleado'] = Empleado::extraerNombresApellidos(Empleado::find($index));
            Log::channel('testing')->info('Log', ['Empleado', $row['empleado'], $atrasosPorEmpleado]);
            $row['segundosAtrasoTotal'] = $atrasosPorEmpleado->sum('segundos_atraso');
            $row['segundosAtrasoJustificado'] = $atrasosPorEmpleado->where('justificado', true)->sum('segundos_atraso');
            $row['segundosAtrasoInjustificado'] = $atrasosPorEmpleado->where('justificado', false)->sum('segundos_atraso');
            $row['minutosAtrasoTotal'] = round($atrasosPorEmpleado->sum('segundos_atraso') / 60, 2);
            $row['minutosAtrasoJustificado'] = round($atrasosPorEmpleado->where('justificado', true)->sum('segundos_atraso') / 60, 2);
            $row['minutosAtrasoInjustificado'] = round($atrasosPorEmpleado->where('justificado', false)->sum('segundos_atraso') / 60, 2);
            $row['horasAtrasoTotal'] = round($row['minutosAtrasoTotal'] / 60, 2);
            $row['horasAtrasoJustificado'] = round($row['minutosAtrasoJustificado'] / 60, 2);
            $row['horasAtrasoInjustificado'] = round($row['minutosAtrasoInjustificado'] / 60, 2);
            $row['momentoMayorOcurrenciaAtraso'] = $atrasosPorEmpleado->countBy('ocurrencia')->sortDesc()->keys()->first();
            $row['cantidadAtrasos'] = $atrasosPorEmpleado->count();
            $row['cantidadAtrasosJustificados'] = $atrasosPorEmpleado->where('justificado', true)->count();
            $row['cantidadAtrasosInjustificados'] = $atrasosPorEmpleado->where('justificado', false)->count();
            $row['cantidadDiasConAtraso'] = $atrasosPorEmpleado->unique('fecha_atraso')->count();
            $empleadosAtrasados[] = $row;
        }
        Log::channel('testing')->info('Log', ['Empleados Atrasados', $empleadosAtrasados]);
        $labels = collect($empleadosAtrasados)->pluck('empleado')->toArray();
        Log::channel('testing')->info('Log', ['labels', $labels]);
        $series = [];
        // LabelOption
        $labelOption = new LabelOption(
            show: true,
            position: 'insideBottom',
            distance: 15,
            align: 'left',
            verticalAlign: 'middle',
            rotate: 90,
            formatter: '{c}',
            fontSize: 12,
            rich: []
        );
        $series[] = new Series(
            'Atrasos',
            'bar',
            collect($empleadosAtrasados)->pluck('cantidadAtrasos')->toArray(),
            null,
            $labelOption,
            ['focus' => 'series']
        );
        $series[] = new Series(
            'DiasConAtraso',
            'bar',
            collect($empleadosAtrasados)->pluck('cantidadDiasConAtraso')->toArray(),
            null,
            $labelOption,
            ['focus' => 'series']
        );
        $series[] = new Series(
            'AtrasosJustificados',
            'bar',
            collect($empleadosAtrasados)->pluck('cantidadAtrasosJustificados')->toArray(),
            null,
            $labelOption, ['focus' => 'series']
        );
        $series[] = new Series(
            'HorasTotalAtraso',
            'bar',
            collect($empleadosAtrasados)->pluck('horasAtrasoTotal')->toArray(),
            null,
            $labelOption, ['focus' => 'series']
        );
        $series[] = new Series(
            'HorasAtrasoJustificadas',
            'bar',
            collect($empleadosAtrasados)->pluck('horasAtrasoJustificado')->toArray(),
            null,
            $labelOption,
            ['focus' => 'series']
        );
        $titulo = "Reporte de atrasos del {$fecha_inicio->format('Y-m-d')} al {$fecha_fin->format('Y-m-d')}";
        $grafico = GraficoEChart::grafico($titulo, "",//"Subtitulo del reporte",
            ['Atrasos', 'DiasConAtraso', 'AtrasosJustificados', 'HorasTotalAtraso', 'HorasAtrasoJustificadas'],
            $labels, $series);
        Log::channel('testing')->info('Log', ['el grafico es', $grafico]);
        return [$grafico, $empleadosAtrasados];
    }

    public function obtenerDashboardAsistencias(Carbon $fecha_inicio, Carbon $fecha_fin)
    {
        // Implementar la logica para obtener las asistencias
        return [];
    }
}
