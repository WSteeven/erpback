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

    public function reporte(Request $request)
    {
        $results = [];

        $results['ingresos_bodega'] = $this->service->obtenerMaterialesIngresadosABodega($request->proyecto_id, $request->tarea_id);
        $results['egresos_bodega'] = $this->service->obtenerMaterialesEgresadosDeBodega($request->proyecto_id, $request->tarea_id);
        $results['devoluciones'] = $this->service->obtenerMaterialesDevueltosABodega($request->proyecto_id, $request->tarea_id);
        $results['preingresos']= $this->service->obtenerMaterialesIngresadosPorPreingresos($request->proyecto_id, $request->tarea_id);
        // $results['transferencias']= $this->service->obtener<texto>;
        return response()->json(compact('results'));
    }
}
