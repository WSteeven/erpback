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
    protected $no_usados;
    protected $seguimiento_stock;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($reporte, $no_usados, $seguimiento_stock)
    {
        $this->reporte = $reporte;
        $this->no_usados = $no_usados;
        $this->seguimiento_stock = $seguimiento_stock;
    }

    public function backgroundColor()
    {
        return new Color(Color::COLOR_WHITE);
    }

    // Agregar pestañas
    public function sheets(): array
    {
        $sheets = [];
        $sheets[1] = new ReporteSeguimientoMaterialStockExport($this->seguimiento_stock);
        $sheets[2] = new ReporteMaterialExport($this->obtenerResumen($this->no_usados), 'Materiales stock no usados en tareas');
        $sheets[3] = new ReporteMaterialExport($this->obtenerResumen($this->reporte), 'Historial de auditoria');
        // $sheets[1] = new ReporteMaterialExport($this->reporte, 'Materiales utilizados');

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
