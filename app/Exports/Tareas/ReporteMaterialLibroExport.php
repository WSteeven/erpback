<?php

namespace App\Exports\Tareas;

use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithBackgroundColor;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Style\Color;

class ReporteMaterialLibroExport implements WithMultipleSheets, WithBackgroundColor
{
    use Exportable;

    protected $reporte;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($reporte)
    {
        $this->reporte = $reporte;
    }

    public function backgroundColor()
    {
        return new Color(Color::COLOR_WHITE);
    }

    // Agregar pestañas
    public function sheets(): array
    {
        $sheets = [];
        $sheets[1] = new ReporteMaterialExport($this->reporte);
        $sheets[2] = new ReporteMaterialExport($this->obtenerResumen($this->reporte));
        // $sheets[2] = new ReporteMaterialResumenExport($this->reporte);

        return $sheets;
    }

    private function obtenerResumen($results)
    {
        $unicos = collect([]);
        foreach ($results as $result) {
            if (count($unicos) == 0) {
                $unicos->push($result);
            } else if ($unicos->contains(function ($unico) use ($result) {
                // Log::channel('testing')->info('Log', ['Item', $unico['detalle_producto_id'] == $item['detalle_producto_id']]);
                // Log::channel('testing')->info('Log', ['Item', $unico['cliente_id'] == $item['cliente']]);
                return !($unico['detalle_producto_id'] == $result['detalle_producto_id']); // && $unico['cliente'] == $result['cliente']);
            })) {
                $unicos->contains(function ($unico) use ($result) {
                    // Log::channel('testing')->info('Log', ['Item', $unico['detalle_producto_id'] == $item['detalle_producto_id']]);
                    // Log::channel('testing')->info('Log', ['Item', $unico['cliente_id'] == $item['cliente']]);
                    return !($unico['detalle_producto_id'] == $result['detalle_producto_id']); // && $unico['cliente'] == $result['cliente']);
                });
                Log::channel('testing')->info('Log', ['Item', $result]);
                Log::channel('testing')->info('Log', ['Dentro de if']);
                // Log::channel('testing')->info('Log', ['Unicos', $unicos]);
                $unicos->push($result);
            }
        };

        Log::channel('testing')->info('Log', ['Return']);
        Log::channel('testing')->info('Log', ['Unicos', $unicos]);
        return $unicos;
    }
}
