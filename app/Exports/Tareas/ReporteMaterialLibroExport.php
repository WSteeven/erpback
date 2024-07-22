<?php

namespace App\Exports\Tareas;

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
        foreach ($results as $item) {
            if ($unicos->contains(function ($unico) use ($item) {
                return $unico['detalle_producto_id'] == $item['detalle_producto_id']
                    && $unico['cliente_id'] == $item['cliente'];
            })) {
                $unicos->push($item);
            }
        };

        return $unicos;
    }
}
