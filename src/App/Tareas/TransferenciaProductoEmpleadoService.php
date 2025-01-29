<?php

namespace Src\App\Tareas;

use App\Http\Resources\Tareas\TransferenciaProductoEmpleadoResource;
use App\Models\Autorizacion;
use App\Models\ConfiguracionGeneral;
use App\Models\Empleado;
use App\Models\MaterialEmpleado;
use App\Models\MaterialEmpleadoTarea;
use App\Models\Tareas\TransferenciaProductoEmpleado;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DateTime;
use Exception;
use Src\Shared\Utils;

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
                if (auth()->user()->hasRole([User::ROL_ADMINISTRADOR, User::ROL_COORDINADOR_BODEGA, User::ROL_AUXILIAR_BODEGA])) {
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
                if (auth()->user()->hasRole([User::ROL_ADMINISTRADOR, User::ROL_COORDINADOR_BODEGA, User::ROL_AUXILIAR_BODEGA])) {
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
                if (auth()->user()->hasRole([User::ROL_ADMINISTRADOR, User::ROL_COORDINADOR_BODEGA, User::ROL_AUXILIAR_BODEGA])) {
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

                if ($productoOrigen) { // de aqui
                    // Restar productos origen
                    $productoOrigen->cantidad_stock -= $producto['cantidad']; // esto tambien
                    $productoOrigen->save(); // y aqui

                    // Sumar productos destino
                    $productoDestino = $esOrigenStock && !$tarea_destino_id ? $this->buscarProductoStock($empleado_destino_id, $producto['id'], $cliente_id) : $this->buscarProductoProyectoEtapaTarea($empleado_destino_id, $producto['id'], $proyecto_destino_id, $etapa_destino_id, $tarea_destino_id, $cliente_id);

                    // Si se encuentra el producto de destino se suma
                    if ($productoDestino) {
                        $productoDestino->cantidad_stock += $producto['cantidad'];
                        $productoDestino->despachado += $producto['cantidad'];
                        $productoDestino->save();
                    } else {
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

    public function imprimirTransferenciaProducto(TransferenciaProductoEmpleado $transferencia_producto_empleado)
    {
        $configuracion = ConfiguracionGeneral::first();
        $fecha_entrega = new DateTime($transferencia_producto_empleado->created_at);

        $pdf = Pdf::loadView('bodega.pdf.transferencia_producto', [
            'configuracion' => $configuracion,
            'transferencia' => (new TransferenciaProductoEmpleadoResource($transferencia_producto_empleado))->resolve(),
            'mes' => Utils::$meses[$fecha_entrega->format('F')],
            'entrega' => Empleado::find($transferencia_producto_empleado->empleado_origen_id),
            'responsable' => Empleado::find($transferencia_producto_empleado->empleado_destino_id),
        ]);
        $pdf->setPaper('A4', 'landscape');
        $pdf->render();
        return $pdf->output();
    }

    public function filtrarTransferenciasPorEmpleadoDestino($request)
    {
        $query = TransferenciaProductoEmpleado::where('empleado_destino_id', $request->responsable)->where('autorizacion_id', TransferenciaProductoEmpleado::APROBADO);

        // Manejo de las fechas usando Carbon
        $fechaInicio = Carbon::parse($request->fecha_inicio)->startOfDay();
        $fechaFin = $request->fecha_fin
            ? Carbon::parse($request->fecha_fin)->endOfDay()
            : now();

        $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);

        return $query->orderByDesc('id')->get();
    }

    public function filtrarTransferenciasPorEmpleadoOrigen($request)
    {
        $query = TransferenciaProductoEmpleado::where('empleado_origen_id', $request->responsable)->where('autorizacion_id', TransferenciaProductoEmpleado::APROBADO);

        // Manejo de las fechas usando Carbon
        $fechaInicio = Carbon::parse($request->fecha_inicio)->startOfDay();
        $fechaFin = $request->fecha_fin
            ? Carbon::parse($request->fecha_fin)->endOfDay()
            : now();

        $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);

        return $query->orderByDesc('id')->get();
    }

    public function obtenerProductosTransferencia($transferencias)
    {
        $results = []; // Inicializa $results fuera del bucle principal

        foreach ($transferencias as $transferencia) {
            $detalles = $transferencia->detallesTransferenciaProductoEmpleado()->get();

            foreach ($detalles as $detalle) {
                $results[] = [
                    'transferencia' => $transferencia->id,
                    'fecha_solicitud' => $transferencia->created_at,
                    'producto' => $detalle->producto->nombre,
                    'descripcion' => $detalle->descripcion,
                    'serial' => $detalle->serial,
                    'categoria' => $detalle->producto->categoria->nombre,
                    'cantidad' => $detalle->pivot->cantidad ?? 0,
                    'cliente' => $transferencia->cliente?->empresa->razon_social,
                    'justificacion' => $transferencia->justificacion,
                    'cliente_id' => $transferencia->cliente_id,
                    'detalle_producto_id' => $detalle->id,
                    'solicitante' => Empleado::extraerNombresApellidos($transferencia->solicitante),
                    'empleado_envia' => Empleado::extraerNombresApellidos($transferencia->empleadoOrigen),
                    'empleado_recibe' => Empleado::extraerNombresApellidos($transferencia->empleadoDestino),
                ];
            }
        }

        return $results;
    }
}
