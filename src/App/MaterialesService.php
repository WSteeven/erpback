<?php

namespace Src\App;

use App\Models\DetalleProducto;
use App\Models\MaterialEmpleado;
use App\Models\MaterialEmpleadoTarea;
use Illuminate\Http\Request;

class MaterialesService
{
    // STOCK PERSONAL
    public function obtenerMaterialesEmpleado(Request $request)
    {
        $request->validate([
            'empleado_id' => 'required|numeric|integer',
        ]);

        $empleado_id = $request['empleado_id'];
        $results = MaterialEmpleado::filter()->where('empleado_id', $empleado_id)->get();

        $results = collect($results)->map(fn ($item, $index) => [
            'item' => $index + 1,
            'detalle_producto' => DetalleProducto::find($item->detalle_producto_id)->descripcion,
            'detalle_producto_id' => $item->detalle_producto_id,
            'stock_actual' => intval($item->cantidad_stock),
            'medida' => 'm',
        ]);

        return $results;
        // return response()->json(compact('results'));
    }

    // TAREAS
    public function obtenerMaterialesEmpleadoTarea(Request $request)
    {
        $request->validate([
            'tarea_id' => 'required|numeric|integer',
            'empleado_id' => 'required|numeric|integer',
        ]);

        $results = MaterialEmpleadoTarea::filter()->get();

        $results = collect($results)->map(fn ($item, $index) => [
            'item' => $index + 1,
            'detalle_producto' => DetalleProducto::find($item->detalle_producto_id)->descripcion,
            'detalle_producto_id' => $item->detalle_producto_id,
            'stock_actual' => intval($item->cantidad_stock),
            'medida' => 'm',
        ]);

        return response()->json(compact('results'));
    }
}
