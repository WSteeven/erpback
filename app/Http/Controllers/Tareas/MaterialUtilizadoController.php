<?php

namespace App\Http\Controllers\Tareas;

use App\Exports\Tareas\ReporteMaterialUtilizadoExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Src\App\Tareas\MaterialesUtilizadosTareaService;
use Maatwebsite\Excel\Facades\Excel;
use Src\App\Tareas\MaterialesUtilizadosStockService;

class MaterialUtilizadoController extends Controller
{
    private MaterialesUtilizadosTareaService $materialesUtilizadosTareaService;
    private MaterialesUtilizadosStockService $materialesUtilizadosStockService;

    public function __construct()
    {
        $this->materialesUtilizadosTareaService = new MaterialesUtilizadosTareaService();
        $this->materialesUtilizadosStockService = new MaterialesUtilizadosStockService();
    }

    public function reporte()
    {
        $reporteTarea = $this->materialesUtilizadosTareaService->init();
        $reporteStock = $this->materialesUtilizadosStockService->init();
        $export = new ReporteMaterialUtilizadoExport($reporteTarea, $this->materialesUtilizadosTareaService, $reporteStock, $this->materialesUtilizadosStockService);
        // return $reporteTarea;
        return Excel::download($export, 'reporte_materiales_utilizados.xlsx');

    }
}
