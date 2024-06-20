<?php

namespace Src\App\Tareas;

use App\Models\DetalleProducto;
use App\Models\DetalleProductoTransaccion;
use App\Models\Empleado;
use App\Models\Inventario;
use App\Models\Motivo;
use App\Models\SeguimientoMaterialStock;
use App\Models\SeguimientoMaterialSubtarea;
use App\Models\Subtarea;
use App\Models\Tareas\DetalleTransferenciaProductoEmpleado;
use App\Models\Tareas\TransferenciaProductoEmpleado;
use App\Models\TipoTransaccion;
use App\Models\TransaccionBodega;
use Illuminate\Support\Facades\Log;
use Src\Config\EstadosTransacciones;

class MaterialesUtilizadosTareaService
{
    public function __construct()
    {
    }

    public static function obtenerMaterialesIngresadosABodega(int|null $proyecto_id = null, int|null $tarea_id = null)
    {
        $tipoTransaccion = TipoTransaccion::where('nombre', TipoTransaccion::INGRESO)->first();
        $ids_motivos = Motivo::where('tipo_transaccion_id', $tipoTransaccion->id)->get('id');
        $results = [];
        $transacciones = TransaccionBodega::where(function ($query) use ($ids_motivos) {
            $query->whereIn('motivo_id', $ids_motivos)
                ->whereIn('estado_id', [EstadosTransacciones::COMPLETA, EstadosTransacciones::PARCIAL]);
        })->when($proyecto_id, function ($query) use ($proyecto_id) {
            $query->where('proyecto_id', $proyecto_id);
        })->when($tarea_id, function ($query) use ($tarea_id) {
            $query->where('tarea_id', $tarea_id);
        })->get();
        Log::channel('testing')->info('Log', ['Transacciones', $transacciones]);

        foreach ($transacciones  as $transaccion) {
            $detalles = DetalleProductoTransaccion::where('transaccion_id', $transaccion->id)->get();
            Log::channel('testing')->info('Log', ['Detalles', $transaccion->id, $detalles]);
        }

        return $results;
    }

    public static function obtenerMaterialesEgresadosDeBodega(int|null $proyecto_id = null, int|null $tarea_id = null)
    {
        $tipoTransaccion = TipoTransaccion::where('nombre', TipoTransaccion::EGRESO)->first();
        $ids_motivos = Motivo::where('tipo_transaccion_id', $tipoTransaccion->id)->get('id');
        $results = [];
        $transacciones = TransaccionBodega::where(function ($query) use ($ids_motivos) {
            $query->whereIn('motivo_id', $ids_motivos)
                ->whereIn('estado_id', [EstadosTransacciones::COMPLETA, EstadosTransacciones::PARCIAL]);
        })->when($proyecto_id, function ($query) use ($proyecto_id) {
            $query->where('proyecto_id', $proyecto_id);
        })->when($tarea_id, function ($query) use ($tarea_id) {
            $query->where('tarea_id', $tarea_id);
        })->get();
        Log::channel('testing')->info('Log', ['Transacciones', $transacciones]);

        $results = self::empaquetarDatos($transacciones);
        Log::channel('testing')->info('Log', ['Resultados', $results]);

        return $results;
    }

    private static function empaquetarDatos($transacciones)
    {
        $results = [];
        $cont = 0;
        foreach ($transacciones  as $transaccion) {
            $detalles = DetalleProductoTransaccion::where('transaccion_id', $transaccion->id)->get();
            Log::channel('testing')->info('Log', ['Detalles', $transaccion->id, $detalles]);
            foreach ($detalles as $detalle) {
                $row['proyecto_id'] = $transaccion->proyecto_id;
                $row['tarea_id'] = $transaccion->tarea_id;
                $row['empleado_id'] = $transaccion->responsable_id;
                $row['empleado'] = Empleado::extraerNombresApellidos($transaccion->responsable);
                $row['tipo'] = $transaccion->motivo->tipoTransaccion->nombre;
                $row['motivo'] = $transaccion->motivo->nombre;
                $detalleProducto = DetalleProducto::find(Inventario::find($detalle->inventario_id)?->detalle_id);
                $row['detalle'] = $detalleProducto?->descripcion;
                $row['detalle_id'] = $detalleProducto?->id;
                $row['cantidad'] = $detalle->recibido;
                $results[$cont] = $row;
                $cont++;
            }
        }
        Log::channel('testing')->info('Log', ['empaquetarDatos', $results]);
        return $results;
    }

    /******************
     * Transferencias
     ******************/
    /**
     * Obtiene el listado de materiales que se han adjuntado en las transferencias de materiales
     * basada en los ID de proyectos y tareas.
     * 
     * @param int $proyecto_id El ID del proyecto para filtrar las transferencias.
     * @param int $tarea_id El ID de la tarea para filtrar las transferencias.
     * @return array Los resultados procesados de las transferencias.
     */
    public static function obtenerMaterialesTransferenciasRecibidas(int|null $proyecto_id = null, int|null $tarea_id = null)
    {
        $transferencias = TransferenciaProductoEmpleado::when($proyecto_id, function ($query) use ($proyecto_id) {
            $query->where('proyecto_destino_id', $proyecto_id);
        })->when($tarea_id, function ($query) use ($tarea_id) {
            $query->where('tarea_destino_id', $tarea_id);
        })->get();

        return self::empaquetarDatosTransferencias($transferencias);
    }

    public static function obtenerMaterialesTransferenciasRecibidasSuma(int|null $proyecto_id = null, int|null $tarea_id = null)
    {
        $transferencias = TransferenciaProductoEmpleado::when($proyecto_id, function ($query) use ($proyecto_id) {
            $query->where('proyecto_destino_id', $proyecto_id);
        })->when($tarea_id, function ($query) use ($tarea_id) {
            $query->where('tarea_destino_id', $tarea_id);
        })->get();

        return self::empaquetarSumaTransferencias($transferencias);
    }

    public static function obtenerMaterialesTransferenciasEnviadasSuma(int|null $proyecto_id = null, int|null $tarea_id = null)
    {
        $transferencias = TransferenciaProductoEmpleado::when($proyecto_id, function ($query) use ($proyecto_id) {
            $query->where('proyecto_origen_id', $proyecto_id);
        })->when($tarea_id, function ($query) use ($tarea_id) {
            $query->where('tarea_origen_id', $tarea_id);
        })->get();

        return self::empaquetarSumaTransferencias($transferencias);
    }

    private static function empaquetarDatosTransferencias($transferencias)
    {
        $results = [];
        $cont = 0;
        foreach ($transferencias  as $transferencia) {
            $detalles = DetalleTransferenciaProductoEmpleado::where('transf_produc_emplea_id', $transferencia->id)->get();

            foreach ($detalles as $detalle) {
                $row['transferencia'] = $transferencia->id;
                $row['proyecto_id'] = $transferencia->proyecto_destino_id;
                $row['tarea_id'] = $transferencia->tarea_destino_id;
                $row['empleado_origen_id'] = $transferencia->empleado_origen_id;
                $row['empleado_id'] = $transferencia->empleado_destino_id;
                $row['empleado'] = Empleado::extraerNombresApellidos($transferencia->empleadoDestino);
                $detalleProducto = $detalle->detalleProducto;
                $row['detalle'] = $detalleProducto?->descripcion;
                $row['detalle_id'] = $detalleProducto?->id;
                $row['cliente_id'] = $transferencia->cliente_id;
                $row['cantidad'] = $detalle->cantidad;
                $results[$cont] = $row;
                $cont++;
            }
        }
        return $results;
    }

    private static function empaquetarSumaTransferencias($transferencias)
    {
        $resultadosAgrupados = [];

        foreach ($transferencias as $transferencia) {
            $detalles = DetalleTransferenciaProductoEmpleado::where('transf_produc_emplea_id', $transferencia->id)->get();

            foreach ($detalles as $detalle) {
                $detalleProducto = $detalle->detalleProducto;

                if ($detalleProducto) {
                    $clave = $detalleProducto->id . '-' . $transferencia->cliente_id;

                    if (!isset($resultadosAgrupados[$clave])) {
                        $resultadosAgrupados[$clave] = [
                            'detalle_id' => $detalleProducto->id,
                            'cliente_id' => $transferencia->cliente_id,
                            'detalle' => $detalleProducto->descripcion,
                            'cantidad' => 0
                        ];
                    }

                    $resultadosAgrupados[$clave]['cantidad'] += $detalle->cantidad;
                }
            }
        }

        // Convertir el array asociativo en un array numérico
        $resultados = array_values($resultadosAgrupados);

        return $resultados;
    }

    /* private static function empaquetarSumaTransferenciasRecibidas2($transferencias)
    {
        $cont = 0;
        foreach ($transferencias  as $transferencia) {
            $detalles = DetalleTransferenciaProductoEmpleado::where('transf_produc_emplea_id', $transferencia->id)->get();

            foreach ($detalles as $detalle) {
                $row['transferencia'] = $transferencia->id;
                $row['proyecto_id'] = $transferencia->proyecto_destino_id;
                $row['tarea_id'] = $transferencia->tarea_destino_id;
                $row['empleado_origen_id'] = $transferencia->empleado_origen_id;
                $row['empleado_id'] = $transferencia->empleado_destino_id;
                $row['empleado'] = Empleado::extraerNombresApellidos($transferencia->empleadoDestino);
                $detalleProducto = $detalle->detalleProducto;
                $row['detalle'] = $detalleProducto?->descripcion;
                $row['detalle_id'] = $detalleProducto?->id;
                $row['cliente_id'] = $transferencia->cliente_id;
                $row['cantidad'] = $detalle->cantidad;
                $results[$cont] = $row;
                $cont++;
            }
        }

        return $results;
    }

    // grupado por cliente_id, empleado_id, detalle_id
    private static function empaquetarSumaTransferenciasRecibidas3($transferencias)
    {
        $resultadosAgrupados = [];

        foreach ($transferencias as $transferencia) {
            $detalles = DetalleTransferenciaProductoEmpleado::where('transf_produc_emplea_id', $transferencia->id)->get();

            foreach ($detalles as $detalle) {
                $detalleProducto = $detalle->detalleProducto;

                if ($detalleProducto) {
                    $clave = $detalleProducto->id . '-' . $transferencia->empleado_destino_id . '-' . $transferencia->cliente_id;

                    if (!isset($resultadosAgrupados[$clave])) {
                        $resultadosAgrupados[$clave] = [
                            'detalle_id' => $detalleProducto->id,
                            'empleado_id' => $transferencia->empleado_destino_id,
                            'cliente_id' => $transferencia->cliente_id,
                            'empleado' => Empleado::extraerNombresApellidos($transferencia->empleadoDestino),
                            'detalle' => $detalleProducto->descripcion,
                            'cantidad' => 0
                        ];
                    }

                    $resultadosAgrupados[$clave]['cantidad'] += $detalle->cantidad;
                }
            }
        }

        // Convertir el array asociativo en un array numérico
        $resultados = array_values($resultadosAgrupados);

        return $resultados;
    } */

    /*************
     * Subtareas
     *************/
    public static function obtenerMaterialesUtilizadosSubtareas(int $tarea_id)
    {
        $subtareas_ids = Subtarea::where('tarea_id', $tarea_id)->pluck('id');
        $seguimientos = SeguimientoMaterialSubtarea::whereIn('subtarea_id', $subtareas_ids)->get();
        return self::empaquetarMaterialesUtilizados($seguimientos);
    }

    public static function obtenerMaterialesUtilizadosStock(int $tarea_id)
    {
        $subtareas_ids = Subtarea::where('tarea_id', $tarea_id)->pluck('id');
        $seguimientos = SeguimientoMaterialStock::whereIn('subtarea_id', $subtareas_ids)->get();
        return self::empaquetarMaterialesUtilizados($seguimientos);
    }

    private static function empaquetarMaterialesUtilizados($seguimientos)
    {
        $resultadosAgrupados = [];

        foreach ($seguimientos as $seguimiento) {
            $detalleProducto = $seguimiento->detalleProducto;

            if ($detalleProducto) {
                $clave = $detalleProducto->id . '-' . $seguimiento->cliente_id;

                if (!isset($resultadosAgrupados[$clave])) {
                    $resultadosAgrupados[$clave] = [
                        'detalle_id' => $detalleProducto->id,
                        'cliente_id' => $seguimiento->cliente_id,
                        'detalle' => $detalleProducto->descripcion,
                        'total_cantidad' => 0
                    ];
                }

                $resultadosAgrupados[$clave]['total_cantidad'] += $seguimiento->cantidad_utilizada;
            }
        }

        // Convertir el array asociativo en un array numérico
        $resultados = array_values($resultadosAgrupados);

        return $resultados;
    }


    /* private static function empaquetarMaterialesUtilizadosTareaold($seguimientos)
    {
        $cont = 0;
        foreach ($seguimientos  as $seguimiento) {
            $detalleProducto = $seguimiento->detalleProducto;

            $row['seguimiento_id'] = $seguimiento->id;
            $row['subtarea_id'] = $seguimiento->subtarea_id;
            $row['empleado_id'] = $seguimiento->empleado_id;
            $row['empleado'] = Empleado::extraerNombresApellidos($seguimiento->empleado);
            $row['detalle'] = $detalleProducto?->descripcion;
            $row['detalle_id'] = $detalleProducto?->id;
            $row['cliente_id'] = $seguimiento->cliente_id;
            $row['cantidad'] = $seguimiento->cantidad_utilizada;
            $results[$cont] = $row;
            $cont++;
        }
        return $results;
    } */
}
