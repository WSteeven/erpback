<?php

namespace App\Http\Controllers;

use App\Models\ControlMaterialSubtarea;
use App\Models\DetalleProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReporteControlMaterialController extends Controller
{
    public function index()
    {
        $tarea = request('tarea');
        $grupo = request('grupo');
        $fecha = request('fecha');

        
        // $results = ControlMaterialSubtarea::where('tarea_id', $tarea)->where('grupo_id', $grupo)->where('fecha', $fecha)->groupBy('detalle_producto_id')->sum('cantidad_utilizada')->get();
        // $results = DB::table('control_materiales_subtareas')->where('tarea_id', $tarea)->where('grupo_id', $grupo)->where('fecha', $fecha)->groupBy('detalle_producto_id')->get();
        
        // Log::channel('testing')->info('Log', ['empresa', $empresa->id]);
        // $consulta = 'SELECT detalle_producto_id, stock_actual, sum(cantidad_utilizada) as utilizado FROM control_materiales_subtareas WHERE tarea_id = ' . 1 . 'and grupo_id = '. 1 . ' GROUP BY detalle_producto_id';
        $consulta = 'SELECT detalle_producto_id, stock_actual, sum(cantidad_utilizada) as cantidad_utilizada FROM control_materiales_subtareas WHERE tarea_id = 1 and grupo_id = 1 GROUP BY detalle_producto_id';
        // $consulta = 'SELECT detalle_producto_id, stock_actual, sum(cantidad_utilizada) as cantidad_utilizada FROM control_materiales_subtareas WHERE tarea_id = ' . 1 . ' and grupo_id = '. 1 . ' GROUP BY detalle_producto_id, stock_actual';
        $results = DB::select($consulta);
        // $results = $this->mapearListado($results);
        return response()->json(compact('results'));
    }

    private function mapearListado($results)
    {
        return $results->map(fn($material) => [
            'detalle_material' => DetalleProducto::find($material->detalle_producto_id)->descripcion,
            'stock_inicial' => $material->stock_actual,
            'utilizado' => $material->cantidad_utilizada,
            'stock_final_dia' => $material->stock_actual - $material->cantidad_utilizada,
        ]);
    }
}
