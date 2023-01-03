<?php

namespace App\Http\Controllers;

use App\Models\ControlMaterialSubtarea;
use App\Models\DetalleProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReporteControlMaterialController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'tarea' => 'required|numeric|integer',
            'grupo' => 'required|numeric|integer',
            'fecha' => 'required|string',
        ]);

        $tarea = request('tarea');
        $grupo = request('grupo');
        $fecha = request('fecha');

        $results = ControlMaterialSubtarea::select(DB::raw('detalle_producto_id, stock_actual, sum(cantidad_utilizada) as cantidad_utilizada'), 'stock_actual')->where('tarea_id', $tarea)->where('grupo_id', $grupo)->groupBy('detalle_producto_id')->where('fecha', $fecha)->get();
        $results = $this->mapearListado($results);

        return response()->json(compact('results'));
    }

    private function mapearListado($results)
    {
        return $results->map(fn ($material, $key) => [
            'item' => $key + 1,
            'detalle_material' => DetalleProducto::find($material->detalle_producto_id)->descripcion,
            'stock_inicial' => $material->stock_actual,
            'utilizado' => $material->cantidad_utilizada,
            'stock_final_dia' => $material->stock_actual - $material->cantidad_utilizada,
        ]);
    }
}
