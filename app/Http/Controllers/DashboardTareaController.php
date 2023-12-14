<?php

namespace App\Http\Controllers;

use Src\App\DashboardTareaService;

class DashboardTareaController extends Controller
{
    private DashboardTareaService $dashboardTareaService;

    public function __construct()
    {
        $this->dashboardTareaService = new DashboardTareaService();
    }

    public function index()
    {
        if (request('empleado_id')) return $this->dashboardTareaService->consultarEmpleado();
        if (request('grupo_id')) return $this->dashboardTareaService->consultarGrupo();
    }
}
