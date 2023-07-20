<?php

namespace Src\App;

use App\Models\MaterialEmpleado;
use App\Models\MaterialEmpleadoTarea;
use App\Models\SeguimientoSubtarea;
use App\Models\SeguimientoMaterialSubtarea;
use App\Models\TrabajoRealizado;
use Carbon\Carbon;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;

class SeguimientoService
{
    public function guardarFotografias($datos, SeguimientoSubtarea $modelo)
    {
        foreach ($datos['trabajo_realizado'] as $trabajo) {
            $trabajoRealizado = new TrabajoRealizado();
            $trabajoRealizado->fotografia = (new GuardarImagenIndividual($trabajo['fotografia'], RutasStorage::SEGUIMIENTO))->execute();
            $trabajoRealizado->fecha_hora = Carbon::parse($trabajo['fecha_hora'])->format('Y-m-d H:i:s');
            $trabajoRealizado->trabajo_realizado = $trabajo['trabajo_realizado'];
            $trabajoRealizado->seguimiento_id = $modelo->id;
            $trabajoRealizado->save();
        }
    }

    /********************
     * Material de tarea
     ********************/
    public function descontarMaterialTareaOcupadoStore($request)
    {
        $materialesOcupados = $request['materiales_tarea_ocupados'];
        $tareaId = $request['tarea_id'];

        foreach ($materialesOcupados as $materialOcupado) {
            $materialEmpleadoTarea = MaterialEmpleadoTarea::where('empleado_id', $request['empleado_id'])->where('detalle_producto_id', $materialOcupado['detalle_producto_id'])->where('tarea_id', $tareaId)->first();
            $materialEmpleadoTarea->cantidad_stock = $materialEmpleadoTarea->cantidad_stock - $materialOcupado['cantidad_utilizada'];
            $materialEmpleadoTarea->save();
        }
    }

    public function descontarMaterialTareaOcupadoUpdate($request)
    {
        $materialesOcupados = $request['materiales_tarea_ocupados'];
        $tareaId = $request['tarea_id'];

        foreach ($materialesOcupados as $materialOcupado) {
            $materialEmpleado = MaterialEmpleadoTarea::where('empleado_id', $request['empleado_id'])->where('detalle_producto_id', $materialOcupado['detalle_producto_id'])->where('tarea_id', $tareaId)->first();
            $materialEmpleado->cantidad_stock += (isset($materialOcupado['cantidad_old']) ? $materialOcupado['cantidad_old'] : 0)  - $materialOcupado['cantidad_utilizada'];
            $materialEmpleado->save();
        }
    }

    public function registrarMaterialTareaOcupadoStore($request)
    {
        $materialesOcupados = $request['materiales_tarea_ocupados'];

        foreach ($materialesOcupados as $materialOcupado) {

            /* SeguimientoMaterialSubtarea::create([
                'stock_actual' => $materialOcupado['stock_actual'],
                'cantidad_utilizada' => $materialOcupado['cantidad_utilizada'],
                'subtarea_id' => $request['subtarea'],
                'empleado_id' => $request['empleado_id'],
                'grupo_id' => $request['grupo_id'],
                'detalle_producto_id' => $materialOcupado['detalle_producto_id'],
            ]); */

            $this->crearMaterialTareaOcupado($materialOcupado, $request);
        }
    }

    public function registrarMaterialTareaOcupadoUpdate($request)
    {
        $materialesOcupados = $request['materiales_tarea_ocupados'];
        $subtareaId = $request['subtarea'];

        foreach ($materialesOcupados as $materialOcupado) {
            $materialSubtarea = SeguimientoMaterialSubtarea::where('empleado_id', $request['empleado_id'])->where('detalle_producto_id', $materialOcupado['detalle_producto_id'])->where('subtarea_id', $subtareaId)->whereDate('created_at', Carbon::today())->first();
            if ($materialSubtarea) {
                $materialSubtarea->cantidad_utilizada +=  $materialOcupado['cantidad_utilizada'];
                $materialSubtarea->save();
            } else {
                $this->crearMaterialTareaOcupado($materialOcupado, $request);
            }
        }
    }

    private function crearMaterialTareaOcupado($materialOcupado, $request) {
        SeguimientoMaterialSubtarea::create([
            'stock_actual' => $materialOcupado['stock_actual'],
            'cantidad_utilizada' => $materialOcupado['cantidad_utilizada'],
            'subtarea_id' => $request['subtarea'],
            'empleado_id' => $request['empleado_id'],
            'grupo_id' => $request['grupo_id'],
            'detalle_producto_id' => $materialOcupado['detalle_producto_id'],
        ]);
    }


    /*****************************
     * Material de stock personal
     *****************************/
    public function descontarMaterialStockOcupadoStore($request)
    {
        $materialesOcupados = $request['materiales_stock_ocupados'];

        foreach ($materialesOcupados as $materialOcupado) {
            $materialEmpleado = MaterialEmpleado::where('empleado_id', $request['empleado_id'])->where('detalle_producto_id', $materialOcupado['detalle_producto_id'])->first();
            $materialEmpleado->cantidad_stock = $materialEmpleado->cantidad_stock - $materialOcupado['cantidad_utilizada'];
            $materialEmpleado->save();
        }
    }

    public function descontarMaterialStockOcupadoUpdate($request)
    {
        $materialesOcupados = $request['materiales_stock_ocupados'];

        foreach ($materialesOcupados as $materialOcupado) {
            $materialEmpleado = MaterialEmpleado::where('empleado_id', $request['empleado_id'])->where('detalle_producto_id', $materialOcupado['detalle_producto_id'])->first();
            $materialEmpleado->cantidad_stock += (isset($materialOcupado['cantidad_old']) ? $materialOcupado['cantidad_old'] : 0)  - $materialOcupado['cantidad_utilizada'];
            $materialEmpleado->save();
        }
    }
}
