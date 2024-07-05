<?php

namespace App\Exports\Tareas;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithBackgroundColor;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Src\App\Tareas\MaterialesUtilizadosTareaService;

class ReporteMaterialUtilizadoExport implements WithMultipleSheets, WithBackgroundColor
{
    use Exportable;

    protected $reporteTarea;
    protected $reporteStock;
    protected $materialesUtilizadosTareaService;
    protected $materialesUtilizadosStockService;

    public function __construct($reporteTarea, $materialesUtilizadosTareaService, $reporteStock, $materialesUtilizadosStockService)
    {
        $this->reporteTarea = $reporteTarea;
        $this->reporteStock = $reporteStock;
        $this->materialesUtilizadosTareaService = $materialesUtilizadosTareaService;
        $this->materialesUtilizadosStockService = $materialesUtilizadosStockService;
    }

    public function backgroundColor()
    {
        return new Color(Color::COLOR_WHITE);
    }

    // Agregar pestañas
    public function sheets(): array
    {
        $sheets = [];
        $sheets[1] = new ReporteMaterialUtilizadoTareaExport($this->reporteTarea, $this->materialesUtilizadosTareaService, 'Proyecto/Etapa/Tarea');
        $sheets[2] = new ReporteMaterialUtilizadoTareaExport($this->reporteStock, $this->materialesUtilizadosStockService, 'Stock');

        return $sheets;
    }
}
