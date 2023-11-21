<?php

namespace Src\App;

use App\Models\MaterialEmpleado;
use App\Models\MaterialEmpleadoTarea;
use App\Models\Tarea;

class TareaService
{
    public function transferirMaterialTareaAStockEmpleados(Tarea $tarea)
    {
        $materialesTarea = MaterialEmpleadoTarea::where('tarea_id', $tarea->id)->get();

        foreach ($materialesTarea as $material) {
            $this->sumarAgregarMaterialEmpleado($material->detalle_producto_id, $material->empleado_id, $material->cantidad_stock, $material->cliente_id);
            $this->restarMaterialEmpleadoTarea($material);
        }
    }

    private function restarMaterialEmpleadoTarea(MaterialEmpleadoTarea $material)
    {
        $material->devuelto = $material->cantidad_stock;
        $material->cantidad_stock = 0;
        $material->save();
    }

    private function sumarAgregarMaterialEmpleado(int $detalle_producto_id, int $empleado_id, int $cantidad_sumar, int $cliente_id)
    {
        $material = MaterialEmpleado::where('detalle_producto_id', $detalle_producto_id)->where('empleado_id', $empleado_id)->first();

        if ($material) {
            $material->cantidad_stock += $cantidad_sumar;
            $material->despachado += $cantidad_sumar;
            $material->cliente_id = $cliente_id;
            $material->save();
        } else {
            MaterialEmpleado::create([
                'cantidad_stock' => $cantidad_sumar,
                'despachado' => $cantidad_sumar,
                'empleado_id' => $empleado_id,
                'detalle_producto_id' => $detalle_producto_id,
                'cliente_id' => $cliente_id,
            ]);
        }
    }
}
