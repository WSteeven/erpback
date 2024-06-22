<?php

namespace Src\App\Tareas;

use App\Models\Autorizacion;
use App\Models\MaterialEmpleado;
use App\Models\MaterialEmpleadoTarea;
use App\Models\Tareas\TransferenciaProductoEmpleado;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

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
                if (auth()->user()->hasRole([User::ROL_ADMINISTRADOR, User::ROL_COORDINADOR_BODEGA])) {
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
                if (auth()->user()->hasRole([User::ROL_ADMINISTRADOR, User::ROL_COORDINADOR_BODEGA])) {
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
                if (auth()->user()->hasRole([User::ROL_ADMINISTRADOR, User::ROL_COORDINADOR_BODEGA])) {
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

        return $consulta->first();
    }

    public function buscarProductoStock($empleado_id, $detalle_producto_id, $cliente_id)
    {
        $consulta = MaterialEmpleado::where('empleado_id', $empleado_id)
            ->where('detalle_producto_id', $detalle_producto_id)
            ->where('cliente_id', $cliente_id)
            ->tieneStock();

        return $consulta->first();
    }

    /********************************************************
     * Restar los productos del origen y sumar en el destino
     ********************************************************/
    public function ajustarValoresProductoOld(TransferenciaProductoEmpleado $transferencia_producto_empleado, bool $esOrigenStock)
    {
        try {
            $cliente_id = $transferencia_producto_empleado->cliente_id; // Trabaja con el cliente de la transferencia

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
                $productoOrigen = $esOrigenStock ? $this->buscarProductoStock($empleado_origen_id, $producto['id'], $cliente_id) : $this->buscarProductoProyectoEtapaTarea($empleado_origen_id, $producto['id'], $proyecto_origen_id, $etapa_origen_id, $tarea_origen_id, $cliente_id);

                Log::channel('testing')->info('Log', compact('productoOrigen'));

                if ($productoOrigen) { // de aqui
                    // Restar productos origen
                    $productoOrigen->cantidad_stock -= $producto['cantidad']; // esto tambien
                    $productoOrigen->save(); // y aqui

                    // Sumar productos destino
                    $productoDestino = $esOrigenStock && !$tarea_destino_id ? $this->buscarProductoStock($empleado_destino_id, $producto['id'], $cliente_id) : $this->buscarProductoProyectoEtapaTarea($empleado_destino_id, $producto['id'], $proyecto_destino_id, $etapa_destino_id, $tarea_destino_id, $cliente_id);

                    // Si se encuentra el producto de destino se suma
                    if ($productoDestino) {
                        Log::channel('testing')->info('se encontro el producto destino');
                        $productoDestino->cantidad_stock += $producto['cantidad'];
                        $productoDestino->despachado += $producto['cantidad'];
                        $productoDestino->save();
                    } else {
                        Log::channel('testing')->info('se crea el producto destino ');
                        // Caso contrario se crea el producto destino
                        // Si no tiene tarea de destino el destino es stock
                        if (!$tarea_destino_id) {
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
                } else { // esto tambien
                    throw new Exception('No existe o no hay stock del producto ' . $producto['descripcion'] . ' del origen seleccionado.'); // esto tambien
                } // esto tambien
            }
        } catch (Exception $e) {
            throw $e; //ValidationException::withMessages(['error' => $e->getMessage()]);
        }
    }


    // ---------------------------------------------------------------------------------
    public function ajustarValoresProducto(TransferenciaProductoEmpleado $transferencia_producto_empleado, bool $esOrigenStock)
    {
        try {
            $cliente_id = $transferencia_producto_empleado->cliente_id; // Trabaja con el cliente de la transferencia

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
                try {
                    if ($esOrigenStock) MaterialEmpleado::descargarMaterialEmpleado($producto['id'], $empleado_origen_id, $producto['cantidad'], $cliente_id, null); // -- Es origen stock
                    else MaterialEmpleadoTarea::descargarMaterialEmpleadoTarea($producto['id'], $empleado_origen_id, $tarea_origen_id, $producto['cantidad'], $cliente_id); // -- Es origen proyecto o tarea cliente final

                    if (!$tarea_destino_id) MaterialEmpleado::cargarMaterialEmpleado($producto['id'], $empleado_destino_id, $producto['cantidad'], $cliente_id); // -- Es destino stock
                    else MaterialEmpleadoTarea::cargarMaterialEmpleadoTarea($producto['id'], $empleado_destino_id, $tarea_destino_id, $producto['cantidad'], $cliente_id, $proyecto_destino_id, $etapa_destino_id); // -- Es destino proyecto o tarea para cliente final   
                } catch (\Throwable $th) {
                    throw $th;
                }
            }
        } catch (Exception $e) {
            throw $e; //ValidationException::withMessages(['error' => $e->getMessage()]);
        }
    }
}
