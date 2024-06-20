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
        // $results = $this->service->obtenerMaterialesEgresadosDeBodega($request->proyecto_id, $request->tarea_id);
        $results = [];

        $results['materiales_utilizados_tarea'] = $this->service->obtenerMaterialesUtilizadosSubtareas($request->tarea_id);
        $results['materiales_utilizados_stock'] = $this->service->obtenerMaterialesUtilizadosStock($request->tarea_id);
        $results['transferencias_recibidas'] = $this->service->obtenerMaterialesTransferenciasRecibidas($request->proyecto_id, $request->tarea_id);
        $results['transferencias_recibidas_suma'] = $this->service->obtenerMaterialesTransferenciasRecibidasSuma($request->proyecto_id, $request->tarea_id);
        $results['transferencias_enviadas_suma'] = $this->service->obtenerMaterialesTransferenciasEnviadasSuma($request->proyecto_id, $request->tarea_id);

        $count_materiales_utilizados_tarea = count($results['materiales_utilizados_tarea']);
        $count_materiales_utilizados_stock = count($results['materiales_utilizados_stock']);
        $transferencias_recibidas = count($results['transferencias_recibidas']);
        $transferencias_recibidas_suma = count($results['transferencias_recibidas_suma']);
        $transferencias_enviadas_suma = count($results['transferencias_enviadas_suma']);
        
        return response()->json(compact(
            'count_materiales_utilizados_tarea',
            'count_materiales_utilizados_stock',
            'transferencias_recibidas',
            'transferencias_recibidas_suma',
            'transferencias_enviadas_suma',
            'results',
        ));
    }
}
