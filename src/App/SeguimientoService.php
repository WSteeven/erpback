<?php

namespace Src\App;

use App\Models\DetalleProducto;
use App\Models\Empleado;
use App\Models\MaterialEmpleado;
use App\Models\MaterialEmpleadoTarea;
use App\Models\Producto;
use App\Models\SeguimientoSubtarea;
use App\Models\SeguimientoMaterialSubtarea;
use App\Models\TrabajoRealizado;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
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

    public function registrarSeguimientoMaterialTareaOcupadoStore($request)
    {
        $materialesOcupados = $request['materiales_tarea_ocupados'];

        foreach ($materialesOcupados as $materialOcupado) {

            $this->crearMaterialTareaOcupado($materialOcupado, $request);
        }
    }

    public function registrarSeguimientoMaterialTareaOcupadoUpdate($request)
    {
        $materialesOcupados = $request['materiales_tarea_ocupados'];
        $subtareaId = $request['subtarea'];

        foreach ($materialesOcupados as $materialOcupado) {
            $materialSubtarea = SeguimientoMaterialSubtarea::where('empleado_id', $request['empleado_id'])->where('detalle_producto_id', $materialOcupado['detalle_producto_id'])->where('subtarea_id', $subtareaId)->whereDate('created_at', Carbon::now()->format('Y-m-d'))->first();
            // $hoy = Carbon::today();
            // Log::channel('testing')->info('Log', compact('hoy'));

            if ($materialSubtarea) {
                $materialSubtarea->cantidad_utilizada =  $materialOcupado['cantidad_utilizada'];
                $materialSubtarea->save();
            } else {
                $this->crearSeguimientoMaterialTareaOcupado($materialOcupado, $request);
            }
        }
    }

    private function crearSeguimientoMaterialTareaOcupado($materialOcupado, $request)
    {
        $grupo_id = Empleado::find($request['empleado_id'])->grupo_id;

        SeguimientoMaterialSubtarea::create([
            'stock_actual' => $materialOcupado['stock_actual'],
            'cantidad_utilizada' => $materialOcupado['cantidad_utilizada'],
            'subtarea_id' => $request['subtarea'],
            'empleado_id' => $request['empleado_id'],
            'grupo_id' => $grupo_id,
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

    // Descontar individual material de tarea
    public function actualizarSeguimientoCantidadUtilizadaMaterialEmpleadoTarea($request)
    {
        $request->validate([
            'empleado_id' => 'required|numeric|integer',
            'tarea_id' => 'required|numeric|integer',
            'subtarea_id' => 'required|numeric|integer',
            'detalle_producto_id' => 'required|numeric|integer',
            'cantidad_utilizada' => 'required|numeric|integer',
            'cantidad_anterior' => 'required|numeric|integer',
            'fecha' => 'required|string',
        ]);

        $idEmpleado = $request['empleado_id'];
        $idSubtarea = $request['subtarea_id'];
        $idDetalleProducto = $request['detalle_producto_id'];
        $cantidadUtilizada = $request['cantidad_utilizada'];
        $fecha = $request['fecha'];

        $materialSubtarea = SeguimientoMaterialSubtarea::where('empleado_id', $idEmpleado)->where('detalle_producto_id', $idDetalleProducto)->where('subtarea_id', $idSubtarea)->whereDate('created_at', Carbon::parse($fecha)->format('Y-m-d'))->first();

        if ($materialSubtarea) {
            $materialSubtarea->cantidad_utilizada =  $cantidadUtilizada;
            $materialSubtarea->save();
        }

        return $this->actualizarDescuentoCantidadUtilizadaMaterialEmpleadoTarea($request);
    }

    public function actualizarDescuentoCantidadUtilizadaMaterialEmpleadoTarea($request)
    {
        $idTarea = $request['tarea_id'];
        $idSubtarea = $request['subtarea_id'];
        $idEmpleado = $request['empleado_id'];
        $idDetalleProducto = $request['detalle_producto_id'];
        $cantidadUtilizada = $request['cantidad_utilizada'];
        $cantidadAnterior = $request['cantidad_anterior'];
        $fecha = $request['fecha'];

        $material = MaterialEmpleadoTarea::where('empleado_id', $idEmpleado)->where('detalle_producto_id', $idDetalleProducto)->where('tarea_id', $idTarea)->first();
        $material->cantidad_stock += (isset($cantidadAnterior) ? $cantidadAnterior : 0)  - $cantidadUtilizada;
        $material->save();

        $detalle = DetalleProducto::find($material->detalle_producto_id);

        $modelo = [
            'producto' => Producto::find($detalle->producto_id)->nombre,
            'detalle_producto' => $detalle->descripcion,
            'detalle_producto_id' => $material->detalle_producto_id,
            'categoria' => $detalle->producto->categoria->nombre,
            'stock_actual' => intval($material->cantidad_stock),
            'despachado' => intval($material->despachado),
            'devuelto' => intval($material->devuelto),
            'cantidad_utilizada' => intval($cantidadUtilizada),
        ];

        $servicio = new TransaccionBodegaEgresoService();
        $materialesUsados = $servicio->obtenerSumaMaterialTareaUsado($idSubtarea, $idEmpleado);

        // $results = $results->map(function ($material, $index) use ($materialesUsados) {
        // if ($materialesUsados->contains('detalle_producto_id', $modelo['detalle_producto_id'])) {
        $modelo['total_cantidad_utilizada'] = intval($materialesUsados->first(function ($item) use ($material) {
            return $item->detalle_producto_id === $material->detalle_producto_id;
        })->suma_total);
        // }
        // $material->id = $index + 1;
        // return $material;
        // });

        return $modelo;
    }
}
