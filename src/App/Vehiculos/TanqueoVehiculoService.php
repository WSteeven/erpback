<?php

namespace Src\App\Vehiculos;

use App\Models\Vehiculos\Combustible;
use App\Models\Vehiculos\Tanqueo;
use App\Models\Vehiculos\Vehiculo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class TanqueoVehiculoService
{

    public function __construct()
    {
    }

    public function dashboard(Request $request)
    {
        // Log::channel('testing')->info('Log', ['obtenerReporteCombustibles:', $request->all()]);
        $results = [];
        $fecha_inicio = Carbon::parse($request->fecha_inicio)->startOfDay();
        $fecha_fin = Carbon::parse($request->fecha_fin)->endOfDay();
        switch ($request->tipo) {
            case Tanqueo::TIPO_RPT_COMBUSTIBLE:
                $results = $this->obtenerReporteCombustibles($fecha_inicio, $fecha_fin, $request->combustible);
                break;
            case Tanqueo::TIPO_RPT_VEHICULO:
                $results = $this->obtenerReporteVehiculos($fecha_inicio, $fecha_fin);
                break;
        }
        return $results;
    }

    private function obtenerReporteCombustibles($fecha_inicio, $fecha_fin, $combustible = null)
    {
        $results = [];
        if (is_null($combustible))
            $tanqueos = Tanqueo::whereBetween('fecha_hora', [$fecha_inicio, $fecha_fin])->get();
        else
            $tanqueos = Tanqueo::where('combustible_id', $combustible)->whereBetween('fecha_hora', [$fecha_inicio, $fecha_fin])->get();
        $resultados_agrupados = $tanqueos->groupBy('combustible_id');
        // Log::channel('testing')->info('Log', ['resultados agrupados:', $resultados_agrupados]);
        foreach ($resultados_agrupados as $index => $resultado) {
            $data = [];
            $data['combustible'] = Combustible::find($index)?->nombre;
            $data['monto'] = round($resultado->sum('monto'), 2);
            $data['recorrido'] = $this->obtenerKilometrosRecorridos($resultado);
            $results[] = $data;
        }
        // Log::channel('testing')->info('Log', ['data:', $results]);
        $labels = [];
        foreach ($results as $item) {
            $labels[] = $item['combustible'];
        }
        $values = [];
        foreach ($results as $item) {
            $values[] = $item['monto'];
        }

        $titulo_grafico = 'Consumo de combustible';
        $graficos = [];

        $grafico = Utils::configurarGrafico(1, 'COMBUSTIBLE', $titulo_grafico, $labels, Utils::coloresAleatorios(), $titulo_grafico, $values);
        $graficos[] = $grafico;
        return compact(
            'graficos',
            'results'
        );
    }
    private function obtenerKilometrosRecorridos($tanqueos)
    {
        $vehiculos_agrupados = $tanqueos->groupBy('vehiculo_id');
        $data = [];
        foreach ($vehiculos_agrupados as $index => $resultado) {
            $data[Vehiculo::find($index)->placa] = $resultado->last()->km_tanqueo - $resultado->first()->km_tanqueo;
        }
        return array_sum($data);
    }
    private function obtenerReporteVehiculos($fecha_inicio, $fecha_fin)
    {
        $results = [];
        $tanqueos = Tanqueo::whereBetween('fecha_hora', [$fecha_inicio, $fecha_fin])->get();
        $resultados_agrupados = $tanqueos->groupBy('vehiculo_id');

         Log::channel('testing')->info('Log', ['resultados:', $tanqueos]);
         Log::channel('testing')->info('Log', ['results agrupados:', $resultados_agrupados]);
        foreach ($resultados_agrupados as $index => $resultado) {
         Log::channel('testing')->info('Log', ['combustible:',$index, $resultado->pluck('combustible_id')]);
            $data = [];
            $data['vehiculo'] = Vehiculo::find($index)?->placa;
            $data['combustible'] = Combustible::find($resultado->pluck('combustible_id')[0])?->nombre;
            $data['monto'] = round($resultado->sum('monto'), 2);
            $data['recorrido'] = $this->obtenerKilometrosRecorridos($resultado);
            $results[] = $data;
        }
        $graficos = [];
         Log::channel('testing')->info('Log', ['results:', $results, $graficos]);
        return compact(
            'graficos',
            'results'
        );
    }
}
