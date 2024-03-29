<?php

namespace Src\App\Tareas;

use App\Models\Autorizacion;
use App\Models\MaterialEmpleado;
use App\Models\MaterialEmpleadoTarea;
use App\Models\Tareas\TransferenciaProductoEmpleado;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class TransferenciaProductoEmpleadoService
{
    /**********************************************************************************
     * Listar las tarnsferencias según su tipo y rol del usuario actual, en el sistema
     **********************************************************************************/
    public static function filtrarTransferencias($request)
    {
        $results = [];
        switch ($request->estado) {
            case Autorizacion::PENDIENTE:
                if (auth()->user()->hasRole([User::ROL_ADMINISTRADOR])) {
                    $results = TransferenciaProductoEmpleado::where('autorizacion_id', Autorizacion::PENDIENTE_ID)->orderBy('updated_at', 'desc')->get();
                } else {
                    $results = TransferenciaProductoEmpleado::where('autorizacion_id', Autorizacion::PENDIENTE_ID)
                        ->where(function ($query) {
                            $query->where('solicitante_id', auth()->user()->empleado->id)
                                ->orWhere('autorizador_id', auth()->user()->empleado->id);
                        })->orderBy('updated_at', 'desc')->get();
                }
                break;
            case Autorizacion::CANCELADO:
                if (auth()->user()->hasRole([User::ROL_ADMINISTRADOR])) {
                    $results = TransferenciaProductoEmpleado::where('autorizacion_id', Autorizacion::CANCELADO_ID)->orderBy('updated_at', 'desc')->get();
                } else {
                    $results = TransferenciaProductoEmpleado::where('autorizacion_id', Autorizacion::CANCELADO_ID)
                        ->where(function ($query) {
                            $query->where('solicitante_id', auth()->user()->empleado->id)
                                ->orWhere('autorizador_id', auth()->user()->empleado->id);
                        })->orderBy('updated_at', 'desc')->get();
                }
                break;
            case Autorizacion::APROBADO:
                if (auth()->user()->hasRole([User::ROL_ADMINISTRADOR])) {
                    $results = TransferenciaProductoEmpleado::where('autorizacion_id', Autorizacion::APROBADO_ID)->orderBy('updated_at', 'desc')->get();
                } else {
                    $results = TransferenciaProductoEmpleado::where('autorizacion_id', Autorizacion::APROBADO_ID)
                        ->where(function ($query) {
                            $query->where('solicitante_id', auth()->user()->empleado->id)
                                ->orWhere('autorizador_id', auth()->user()->empleado->id);
                        })->orderBy('updated_at', 'desc')->get();
                }
                break;
            default:
                $results = TransferenciaProductoEmpleado::where('solicitante_id', auth()->user()->empleado->id)->orWhere('autorizador_id', auth()->user()->empleado->id)->orderBy('updated_at', 'desc')->get();
        }
        return $results;
    }

    /************************
     * Busqueda de productos
     ************************/
    public function buscarProductoProyectoEtapaTarea($empleado_id, $detalle_producto_id, $proyecto_id, $etapa_id, $tarea_id, $cliente_id)
    {
        $consulta = MaterialEmpleadoTarea::where('empleado_id', $empleado_id)
            ->where('detalle_producto_id', $detalle_producto_id)
            ->where('proyecto_id', $proyecto_id)
            ->where('etapa_id', $etapa_id)
            ->where('cliente_id', $cliente_id)
            ->tieneStock();

        if ($tarea_id) $consulta = $consulta->where('tarea_id', $tarea_id);

        // $sql = $consulta->toSql();
        // $bin = $consulta->getBindings();

        // Log::channel('testing')->info(__FILE__ . '/' . basename(__LINE__) . ')', compact('sql', 'bin'));
        // Log::channel('testing')->info(__FILE__ . '/' . basename(__LINE__) . ')', compact('empleado_id', 'detalle_producto_id', 'proyecto_id', 'etapa_id', 'tarea_id', 'cliente_id'));

        return $consulta->first();
    }

    public function buscarProductoStock($empleado_id, $detalle_producto_id, $cliente_id)
    {
        $consulta = MaterialEmpleado::where('empleado_id', $empleado_id)
            ->where('detalle_producto_id', $detalle_producto_id)
            ->where('cliente_id', $cliente_id)
            ->tieneStock();

        // $sql = $consulta->toSql();
        // $bin = $consulta->getBindings();

        // Log::channel('testing')->info(__FILE__ . '/' . basename(__LINE__) . ')', compact('sql', 'bin'));
        // Log::channel('testing')->info(__FILE__ . '/' . basename(__LINE__) . ')', compact('empleado_id', 'detalle_producto_id', 'cliente_id'));

        return $consulta->first();
    }

    /********************************************************
     * Restar los productos del origen y sumar en el destino
     ********************************************************/
    public function ajustarValoresProducto(TransferenciaProductoEmpleado $transferencia_producto_empleado, bool $esOrigenStock)
    {
        $cliente_id = $transferencia_producto_empleado->cliente_id; // request('cliente');
        // Log::channel('testing')->info(__FILE__ . '/' . basename(__LINE__) . ') Cliente: ' . $cliente_id);

        // Origen
        $empleado_origen_id = request('empleado_origen');
        $proyecto_origen_id = request('proyecto_origen');
        $etapa_origen_id = request('etapa_origen');
        $tarea_origen_id = request('tarea_origen');

        // Destino
        $empleado_destino_id = request('empleado_destino');
        $proyecto_destino_id = request('proyecto_destino');
        $etapa_destino_id = request('etapa_destino');
        $tarea_destino_id = request('tarea_destino');

        foreach (request('listado_productos') as $producto) {
            // Restar productos origen
            $productoOrigen = $esOrigenStock ? $this->buscarProductoStock($empleado_origen_id, $producto['id'], $cliente_id) : $this->buscarProductoProyectoEtapaTarea($empleado_origen_id, $producto['id'], $proyecto_origen_id, $etapa_origen_id, $tarea_origen_id, $cliente_id);

            $productoOrigen->cantidad_stock -= $producto['cantidad'];
            $productoOrigen->save();

            // Log::channel('testing')->info('Log', compact('productoOrigen'));

            if ($productoOrigen) {
                // Sumar productos destino
                // $productoDestino = $esOrigenStock ? $this->buscarProductoStock($empleado_destino_id, $producto['id'], $cliente_id) : $this->buscarProductoProyectoEtapaTarea($empleado_destino_id, $producto['id'], $proyecto_destino_id, $etapa_destino_id, $tarea_destino_id, $cliente_id);
                $productoDestino = $esOrigenStock && !$tarea_destino_id ? $this->buscarProductoStock($empleado_destino_id, $producto['id'], $cliente_id) : $this->buscarProductoProyectoEtapaTarea($empleado_destino_id, $producto['id'], $proyecto_destino_id, $etapa_destino_id, $tarea_destino_id, $cliente_id);

                if ($productoDestino) {
                    // $mensaje = 'Si se encuentra';
                    // Log::channel('testing')->info(__FILE__ . '/' . basename(__LINE__) . ')', compact('mensaje'));
                    $productoDestino->cantidad_stock += $producto['cantidad'];
                    $productoDestino->despachado += $producto['cantidad'];
                    $productoDestino->save();
                } else {
                    // $mensaje = 'Si no se encuentra, se crea';
                    // Log::channel('testing')->info(__FILE__ . '/' . basename(__LINE__) . ')', compact('mensaje'));

                    if ($esOrigenStock && !$tarea_destino_id) {
                        $productoDestino = MaterialEmpleado::create([
                            'empleado_id' => $empleado_destino_id,
                            'cantidad_stock' => $producto['cantidad'],
                            'detalle_producto_id' => $producto['id'],
                            'despachado' => $producto['cantidad'],
                            'cliente_id' => $cliente_id,
                        ]);
                    } else {
                        $productoDestino = MaterialEmpleadoTarea::create([
                            'empleado_id' => $empleado_destino_id,
                            'cantidad_stock' => $producto['cantidad'],
                            'detalle_producto_id' => $producto['id'],
                            'despachado' => $producto['cantidad'],
                            'proyecto_id' => $proyecto_destino_id,
                            'etapa_id' => $etapa_destino_id,
                            'tarea_id' => $tarea_destino_id,
                            'cliente_id' => $cliente_id,
                        ]);
                    }
                }

                // Log::channel('testing')->info(__FILE__ . '/' . basename(__LINE__) . ')', compact('productoDestino'));
            }
        }
    }
}
