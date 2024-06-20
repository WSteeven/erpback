<?php

namespace Src\App\Tareas;

use App\Models\Autorizacion;
use App\Models\DetalleDevolucionProducto;
use App\Models\DetalleProducto;
use App\Models\DetalleProductoTransaccion;
use App\Models\Devolucion;
use App\Models\Empleado;
use App\Models\EstadoTransaccion;
use App\Models\Inventario;
use App\Models\ItemDetallePreingresoMaterial;
use App\Models\Motivo;
use App\Models\SeguimientoMaterialStock;
use App\Models\SeguimientoMaterialSubtarea;
use App\Models\Subtarea;
use App\Models\Tareas\DetalleTransferenciaProductoEmpleado;
use App\Models\Tareas\TransferenciaProductoEmpleado;
use App\Models\PreingresoMaterial;
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

        $results = self::empaquetarDatosTransaccion($transacciones);
        Log::channel('testing')->info('Log', ['Resultados', $results]);

        return $results;
    }
    public static function obtenerMaterialesDevueltosABodega(int|null $proyecto_id = null, int|null $tarea_id = null)
    {
        $results = [];
        $devoluciones = [];
        if ($proyecto_id) {
            $devoluciones = Devolucion::whereIn('estado_bodega', [EstadoTransaccion::COMPLETA, EstadoTransaccion::PARCIAL])
                ->whereHas('tarea', function ($query) use ($proyecto_id) {
                    $query->where('proyecto_id', $proyecto_id);
                })
                ->get();
            Log::channel('testing')->info('Log', ['Devoluciones obtenidas cuando se pasa proyecto', $devoluciones]);
        }
        if ($tarea_id) {
            $devoluciones = Devolucion::whereIn('estado_bodega', [EstadoTransaccion::COMPLETA, EstadoTransaccion::PARCIAL])
                ->where('tarea_id', $tarea_id)->get();
            Log::channel('testing')->info('Log', ['Devoluciones obtenidas cuando se pasa tarea', $devoluciones]);
        }

        $results = self::empaquetarDatosDevolucion($devoluciones);
        Log::channel('testing')->info('Log', ['Resultados devoluciones', $results]);
        return $results;
    }

    public static function obtenerMaterialesIngresadosPorPreingresos(int|null $proyecto_id = null, int|null $tarea_id = null)
    {
        $results = [];
        $preingresos = [];
        if ($proyecto_id) {
            $preingresos = PreingresoMaterial::where('autorizacion_id', Autorizacion::APROBADO_ID)
                ->whereHas('tarea', function ($query) use ($proyecto_id) {
                    $query->where('proyecto_id', $proyecto_id);
                })->get();
            Log::channel('testing')->info('Log', ['Preingresos cuando se pasa proyecto', $preingresos]);
        }
        if ($tarea_id) {
            $preingresos = PreingresoMaterial::where('autorizacion_id', Autorizacion::APROBADO_ID)
                ->where('tarea_id', $tarea_id)->get();
            Log::channel('testing')->info('Log', ['Preingresos obtenidos cuando se pasa tarea', $preingresos]);
        }

        $results = self::empaquetarDatosPreingreso($preingresos);
        Log::channel('testing')->info('Log', ['Resultados preingresos', $results]);

        return $results;
    }


    private static function empaquetarDatosTransaccion($transacciones)
    {
        $results = [];
        $cont = 0;
        foreach ($transacciones  as $transaccion) {
            $detalles = DetalleProductoTransaccion::where('transaccion_id', $transaccion->id)->get();
            // Log::channel('testing')->info('Log', ['Detalles', $transaccion->id, $detalles]);
            foreach ($detalles as $detalle) {
                $detalleProducto = DetalleProducto::find(Inventario::find($detalle->inventario_id)?->detalle_id);
                $results[$cont] = self::empaquetarDatosIndividual($transaccion->proyecto_id, $transaccion->tarea_id, $transaccion->responsable_id, $transaccion->motivo->nombre, $detalleProducto);
                $cont++;
            }
        }
        Log::channel('testing')->info('Log', ['empaquetarDatosTransaccion', $results]);
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

    private static function empaquetarDatosPreingreso($preingresos)
    {
        $results = [];
        $cont = 0;
        foreach ($preingresos  as $preingreso) {
            $detalles = DetalleProducto::whereIn('id', ItemDetallePreingresoMaterial::where('preingreso_id', $preingreso->id)->pluck('detalle_id'))->get();
            foreach ($detalles as $detalle) {
                $results[$cont] = self::empaquetarDatosIndividual($preingreso->tarea->proyecto_id, $preingreso->tarea_id, $preingreso->responsable_id, $preingreso->observacion, $detalle);
                $cont++;
            }
        }
        Log::channel('testing')->info('Log', ['empaquetarDatosPreingreso', $results]);
        return $results;
    }
    
    private static function empaquetarDatosDevolucion($devoluciones)
    {
        $results = [];
        $cont = 0;
        foreach ($devoluciones  as $devolucion) {
            $detalles = DetalleProducto::whereIn('id', DetalleDevolucionProducto::where('devolucion_id', $devolucion->id)->pluck('detalle_id'))->get();
            foreach ($detalles as $detalle) {
                $results[$cont] = self::empaquetarDatosIndividual($devolucion->tarea->proyecto_id, $devolucion->tarea_id, $devolucion->solicitante_id, $devolucion->justificacion, $detalle);
                $cont++;
            }
        }
        Log::channel('testing')->info('Log', ['empaquetarDatosTransaccion', $results]);
        return $results;
    }

    /**
     * La función "empaquetarDatosIndividual" empaqueta datos individuales relacionados con una tarea
     * del proyecto, incluyendo ID del proyecto, ID de la tarea, ID del empleado responsable, motivo,
     * detalles del producto y cantidad.
     * 
     * @param int|null $proyecto_id El parámetro `proyecto_id` es un número entero que representa el ID del
     * proyecto. Puede ser "nulo" si no hay ningún proyecto asociado con los datos que se procesan.
     * @param int|null $tarea_id Es un parámetro entero opcional que representa el ID de una tarea 
     * asociada con los datos que se están procesando. 
     * @param int $responsable_id Representa el ID del empleado que es responsable del elemento en particular. 
     * Esta función se utiliza para empaquetar datos individuales para una
     * tarea o proyecto, incluidos detalles como el ID del proyecto, el ID de la tarea, el responsable
     * @param string $motivo El parámetro `motivo` en la función `empaquetarDatosIndividual` es un
     * parámetro de cadena que representa el motivo o motivo asociado con los datos que se están
     * procesando. Es un parámetro opcional como lo indica `= null` en la firma de la función, lo que
     * significa que se puede proporcionar pero no está disponible.
     * @param DetalleProducto $detalle El parámetro `detalle` en la función `empaquetarDatosIndividual`
     * es un objeto de tipo `DetalleProducto`. Contiene información sobre el detalle de un producto,
     * como su descripción, ID y cantidad recibida.
     * 
     * @return array Se devuelve una matriz con las siguientes claves y valores:
     * - 'proyecto_id': el valor del parámetro 
     * - 'tarea_id': el valor del parámetro 
     * - 'empleado_id': el valor del parámetro 
     * - 'empleado': resultado de extraer los nombres y apellidos del empleado con el
     */
    private static function empaquetarDatosIndividual(int|null $proyecto_id, int|null $tarea_id = null, int $responsable_id, string $motivo = null, DetalleProducto $detalle)
    {
        $row = [];
        $row['proyecto_id'] = $proyecto_id;
        $row['tarea_id'] = $tarea_id;
        $row['empleado_id'] = $responsable_id;
        $row['empleado'] = Empleado::extraerNombresApellidos(Empleado::find($responsable_id));
        $row['motivo'] = $motivo;
        $row['unidad_medida'] = $detalle->producto->unidadMedida->nombre;
        $row['detalle'] = $detalle->descripcion;
        $row['detalle_id'] = $detalle->id;
        $row['cantidad'] = $detalle->recibido;

        return $row;
    }
}
