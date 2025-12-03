<?php

namespace Src\App;

use App\Models\Cliente;
use App\Models\DetalleProducto;
use App\Models\Empleado;
use App\Models\MaterialEmpleado;
use App\Models\MaterialEmpleadoTarea;
use App\Models\Producto;
use App\Models\SeguimientoMaterialStock;
use App\Models\SeguimientoSubtarea;
use App\Models\SeguimientoMaterialSubtarea;
use App\Models\TrabajoRealizado;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Illuminate\Support\Facades\DB;

class SeguimientoService
{
    public function guardarTrabajosRealizados($datos, SeguimientoSubtarea $modelo)
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
    /* public function descontarMaterialTareaOcupadoStore($request)
    {
        $materialesOcupados = $request['materiales_tarea_ocupados'];
        $idTarea = $request['tarea_id'];
        $idEmpleado = $request['empleado_id'];

        foreach ($materialesOcupados as $materialOcupado) {
            $idDetalleProducto = $materialOcupado['detalle_producto_id'];

            $materialEmpleadoTarea = MaterialEmpleadoTarea::where('empleado_id', $idEmpleado)->where('detalle_producto_id', $idDetalleProducto)->where('tarea_id', $idTarea)->first();
            $materialEmpleadoTarea->cantidad_stock = $materialEmpleadoTarea->cantidad_stock - $materialOcupado['cantidad_utilizada'];
            $materialEmpleadoTarea->save();
        }
    } */

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
    //

    // Descontar individual material de tarea
    public function actualizarSeguimientoCantidadUtilizadaMaterialEmpleadoTarea($request)
    {
        return DB::transaction(function () use ($request) {
            $request->validate([
                'empleado_id' => 'required|numeric|integer',
                'tarea_id' => 'required|numeric|integer',
                'subtarea_id' => 'required|numeric|integer',
                'detalle_producto_id' => 'required|numeric|integer',
                'cantidad_utilizada' => 'required|numeric|integer',
                'cantidad_anterior' => 'required|numeric|integer',
            ]);

            $idEmpleado = $request['empleado_id'];
            $idSubtarea = $request['subtarea_id'];
            $idDetalleProducto = $request['detalle_producto_id'];
            $cantidadUtilizada = $request['cantidad_utilizada'];
            $cliente_id = $request['cliente_id'];

            $materialSubtarea = SeguimientoMaterialSubtarea::where('empleado_id', $idEmpleado)->where('detalle_producto_id', $idDetalleProducto)->where('subtarea_id', $idSubtarea)->where('cliente_id', $cliente_id)->whereDate('created_at', Carbon::now()->format('Y-m-d'))->first();

            if ($materialSubtarea) {
                $materialSubtarea->cantidad_utilizada =  $cantidadUtilizada;
                $materialSubtarea->save();
            } else {
                $idGrupo = Empleado::find($request['empleado_id'])->grupo_id;

                SeguimientoMaterialSubtarea::create([
                    'cantidad_utilizada' => $cantidadUtilizada,
                    'subtarea_id' => $idSubtarea,
                    'empleado_id' => $idEmpleado,
                    'grupo_id' => $idGrupo,
                    'detalle_producto_id' => $idDetalleProducto,
                    'cliente_id' => $cliente_id,
                ]);
            }

            return $this->actualizarDescuentoCantidadUtilizadaMaterialEmpleadoTarea($request);
        });
    }

    public function actualizarSeguimientoCantidadUtilizadaMaterialEmpleadoTareaHistorial($request)
    {
        $request->validate([
            'empleado_id' => 'required|numeric|integer',
            'tarea_id' => 'required|numeric|integer',
            'subtarea_id' => 'required|numeric|integer',
            'detalle_producto_id' => 'required|numeric|integer',
            'cantidad_utilizada' => 'required|numeric|integer',
            'cantidad_anterior' => 'required|numeric|integer',
            'fecha' => 'required|string',
            'cliente_id' => 'nullable|numeric|integer',
        ]);

        $idEmpleado = $request['empleado_id'];
        $idSubtarea = $request['subtarea_id'];
        $idDetalleProducto = $request['detalle_producto_id'];
        $cantidadUtilizada = $request['cantidad_utilizada'];
        $fecha = $request['fecha'];

        $materialSubtarea = SeguimientoMaterialSubtarea::where('empleado_id', $idEmpleado)->where('detalle_producto_id', $idDetalleProducto)->where('cliente_id', $request['cliente_id'])->where('subtarea_id', $idSubtarea)->whereDate('created_at', Carbon::parse($fecha)->format('Y-m-d'))->first();

        if ($materialSubtarea) {
            $materialSubtarea->cantidad_utilizada =  $cantidadUtilizada;
            $materialSubtarea->save();
        }

        return $this->actualizarDescuentoCantidadUtilizadaMaterialEmpleadoTarea($request);
    }

    public function actualizarSeguimientoCantidadUtilizadaMaterialEmpleadoStockHistorial($request)
    {
        $request->validate([
            'empleado_id' => 'required|numeric|integer',
            'subtarea_id' => 'required|numeric|integer',
            'detalle_producto_id' => 'required|numeric|integer',
            'cantidad_utilizada' => 'required|numeric|integer',
            'cantidad_anterior' => 'required|numeric|integer',
            'fecha' => 'required|string',
            'cliente_id' => 'nullable|numeric|integer',
        ]);

        $idEmpleado = $request['empleado_id'];
        $idSubtarea = $request['subtarea_id'];
        $idDetalleProducto = $request['detalle_producto_id'];
        $cantidadUtilizada = $request['cantidad_utilizada'];
        $fecha = $request['fecha'];
        $cliente_id = $request['cliente_id'];

        $materialSubtarea = SeguimientoMaterialStock::where('empleado_id', $idEmpleado)->where('detalle_producto_id', $idDetalleProducto)->where('subtarea_id', $idSubtarea)->where('cliente_id', $cliente_id)->whereDate('created_at', Carbon::parse($fecha)->format('Y-m-d'))->first();

        if ($materialSubtarea) {
            $materialSubtarea->cantidad_utilizada =  $cantidadUtilizada;
            $materialSubtarea->save();
        }

        return $this->actualizarDescuentoCantidadUtilizadaMaterialEmpleadoStock($request);
    }

    public function actualizarDescuentoCantidadUtilizadaMaterialEmpleadoTarea($request)
    {
        $idTarea = $request['tarea_id'];
        $etapa_id = $request['etapa_id'];
        $proyecto_id = $request['proyecto_id'];
        $cliente_id = $request['cliente_id'];
        $idSubtarea = $request['subtarea_id'];
        $idEmpleado = $request['empleado_id'];
        $idDetalleProducto = $request['detalle_producto_id'];
        $cantidadUtilizada = $request['cantidad_utilizada'];
        $cantidadAnterior = $request['cantidad_anterior'];

        if ($proyecto_id) {
            $material = MaterialEmpleadoTarea::where('empleado_id', $idEmpleado)->where('detalle_producto_id', $idDetalleProducto)->where('etapa_id', $etapa_id)->where('proyecto_id', $proyecto_id)->where('cliente_id', $cliente_id)->first();
        } else {
            $material = MaterialEmpleadoTarea::where('empleado_id', $idEmpleado)->where('detalle_producto_id', $idDetalleProducto)->where('tarea_id', $idTarea)->where('etapa_id', $etapa_id)->where('proyecto_id', $proyecto_id)->where('cliente_id', $cliente_id)->first();
        }
        $material->cantidad_stock += (isset($cantidadAnterior) ? $cantidadAnterior : 0)  - $cantidadUtilizada;
        $material->save();

        $detalle = DetalleProducto::find($material->detalle_producto_id);
        $producto = Producto::find($detalle->producto_id);

        $modelo = [
            'id' => $material->detalle_producto_id,
            'producto' => Producto::find($detalle->producto_id)->nombre,
            'detalle_producto' => $detalle->descripcion,
            'detalle_producto_id' => $material->detalle_producto_id,
            'categoria' => $detalle->producto->categoria->nombre,
            'stock_actual' => intval($material->cantidad_stock),
            'despachado' => intval($material->despachado),
            'devuelto' => intval($material->devuelto),
            'cantidad_utilizada' => intval($cantidadUtilizada),
            'medida' => $producto->unidadMedida?->simbolo,
            'serial' => $detalle->serial,
            'cliente' => $material->cliente_id ? Cliente::find($material->cliente_id)->empresa->razon_social : null,
            'cliente_id' => $material->cliente_id,
        ];

        $servicio = new TransaccionBodegaEgresoService();
        $materialesUsados = $servicio->obtenerSumaMaterialTareaUsado($idSubtarea, $idEmpleado);

        $modelo['total_cantidad_utilizada'] = intval($materialesUsados->first(function ($item) use ($material) {
            return $item->detalle_producto_id === $material->detalle_producto_id && $item->cliente_id === $material->cliente_id;
        })?->suma_total);

        return $modelo;
    }

    /*********
     * Stock
     *********/
    public function actualizarSeguimientoCantidadUtilizadaMaterialEmpleadoStock($request)
    {
        $request->validate([
            'empleado_id' => 'required|numeric|integer',
            'subtarea_id' => 'required|numeric|integer',
            'detalle_producto_id' => 'required|numeric|integer',
            'cantidad_utilizada' => 'required|numeric|integer',
            'cantidad_anterior' => 'required|numeric|integer',
        ]);

        $idEmpleado = $request['empleado_id'];
        $idSubtarea = $request['subtarea_id'];
        $idDetalleProducto = $request['detalle_producto_id'];
        $cantidadUtilizada = $request['cantidad_utilizada'];
        $cliente_id = $request['cliente_id'];


        $materialSubtarea = SeguimientoMaterialStock::where('empleado_id', $idEmpleado)->where('detalle_producto_id', $idDetalleProducto)->where('subtarea_id', $idSubtarea)->where('cliente_id', $cliente_id)->whereDate('created_at', Carbon::now()->format('Y-m-d'))->first();

        if ($materialSubtarea) {
            $materialSubtarea->cantidad_utilizada =  $cantidadUtilizada;
            $materialSubtarea->save();
        } else {
            SeguimientoMaterialStock::create([
                'cantidad_utilizada' => $cantidadUtilizada,
                'subtarea_id' => $idSubtarea,
                'empleado_id' => $idEmpleado,
                'detalle_producto_id' => $idDetalleProducto,
                'cliente_id' => $cliente_id,
            ]);
        }

        return $this->actualizarDescuentoCantidadUtilizadaMaterialEmpleadoStock($request);
    }

    public function actualizarDescuentoCantidadUtilizadaMaterialEmpleadoStock($request)
    {
        $idSubtarea = $request['subtarea_id'];
        $idEmpleado = $request['empleado_id'];
        $idDetalleProducto = $request['detalle_producto_id'];
        $cantidadUtilizada = $request['cantidad_utilizada'];
        $cantidadAnterior = $request['cantidad_anterior'];
        $cliente_id = $request['cliente_id'];

        $material = MaterialEmpleado::where('empleado_id', $idEmpleado)->where('detalle_producto_id', $idDetalleProducto)->where('cliente_id', $cliente_id)->first();
        $material->cantidad_stock += ($cantidadAnterior ?? 0)  - $cantidadUtilizada;
        $material->save();

        $detalle = DetalleProducto::find($material->detalle_producto_id);
        $producto = Producto::find($detalle->producto_id);

        $modelo = [
            'producto' => $producto->nombre,
            'detalle_producto' => $detalle->descripcion,
            'detalle_producto_id' => $material->detalle_producto_id,
            'categoria' => $detalle->producto->categoria->nombre,
            'stock_actual' => intval($material->cantidad_stock),
            'despachado' => intval($material->despachado),
            'devuelto' => intval($material->devuelto),
            'cantidad_utilizada' => intval($cantidadUtilizada),
            'medida' => $producto->unidadMedida?->simbolo,
            'serial' => $detalle->serial,
            'cliente' => $material->cliente_id ? Cliente::find($material->cliente_id)->empresa->razon_social : null,
            'cliente_id' => $material->cliente_id,
        ];

        $servicio = new TransaccionBodegaEgresoService();
        $materialesUsados = $servicio->obtenerSumaMaterialStockUsado($idSubtarea, $idEmpleado, $cliente_id);

        $modelo['total_cantidad_utilizada'] = intval($materialesUsados->first(function ($item) use ($material) {
            return $item->detalle_producto_id === $material->detalle_producto_id;
        })?->suma_total);

        return $modelo;
    }
}
