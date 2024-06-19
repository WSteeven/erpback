<?php

namespace App\Http\Controllers\Tareas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Src\App\Tareas\MaterialesUtilizadosTareaService;

class MaterialUtilizadoController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new MaterialesUtilizadosTareaService();
    }

    public function reporte(Request $request){
        $this->service->obtenerMaterialesEgresadosDeBodega($request->proyecto_id, $request->tarea_id);
    }

    
}
