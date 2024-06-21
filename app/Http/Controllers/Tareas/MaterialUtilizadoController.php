<?php

namespace App\Http\Controllers\Tareas;

use App\Exports\Tareas\ReporteMaterialUtilizadoExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Src\App\Tareas\MaterialesUtilizadosTareaService;
use Maatwebsite\Excel\Facades\Excel;

class MaterialUtilizadoController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new MaterialesUtilizadosTareaService();
    }

    public function reporte(Request $request)
    {
        $reporte = $this->service->init();
        $export = new ReporteMaterialUtilizadoExport($reporte, $this->service);
        return $reporte;
        return Excel::download($export, 'reporte_materiales_utilizados.xlsx');
    }
}
