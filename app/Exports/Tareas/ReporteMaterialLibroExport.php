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
        $sheets[1] = new ReporteMaterialExport($this->reporte, 'Reporte de materiales');
        $sheets[2] = new ReporteMaterialExport($this->obtenerResumen($this->reporte), 'Resumen reporte de materiales');

        return $sheets;
    }

    private function obtenerResumen($results)
    {
        $unicos = collect([]);

        foreach ($results as $result) {
            if (!$unicos->contains(function ($unico) use ($result) {
                return $unico['detalle_producto_id'] . $unico['cliente'] == $result['detalle_producto_id'] . $result['cliente'];
            }))
                $unicos->push($result);
        };

        return $unicos;
    }
}
