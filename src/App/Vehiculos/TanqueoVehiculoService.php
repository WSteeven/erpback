<?php

namespace Src\App\Vehiculos;

use App\Models\Vehiculos\Combustible;
use App\Models\Vehiculos\Tanqueo;
use App\Models\Vehiculos\Vehiculo;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        $fecha_inicio = Carbon::parse($request->fecha_inicio);
        $fecha_fin = Carbon::parse($request->fecha_fin);
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
            $data['combustible'] = Combustible::find($index)->nombre;
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

        $tituloGrafico = 'Consumo de combustible';
        $graficos = [];

        $grafico = Utils::configurarGrafico(1, 'COMBUSTIBLE', $tituloGrafico, $labels, Utils::coloresAleatorios(), $tituloGrafico, $values);
        array_push($graficos, $grafico);
        return compact(
            'graficos',
            'results'
        );
    }
    private function obtenerKilometrosRecorridos($tanqueos)
    {
        $vehiculos_agrupados = $tanqueos->groupBy('vehiculo_id');
        foreach ($vehiculos_agrupados as $index => $resultado) {
            $data[Vehiculo::find($index)->placa] = $resultado->last()->km_tanqueo - $resultado->first()->km_tanqueo;
        }
        return array_sum($data);
    }
    private function obtenerReporteVehiculos($fecha_inicio, $fecha_fin)
    {
        $results = [];
        $graficos = [];
        return compact(
            'graficos',
            'results'
        );
    }
}
