<?php

namespace Src\App\Tareas;

use App\Models\DetalleProducto;
use App\Models\DetalleProductoTransaccion;
use App\Models\Empleado;
use App\Models\Inventario;
use App\Models\Motivo;
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
                $row['detalle'] = $detalleProducto->descripcion;
                $row['detalle_id'] = $detalleProducto->id;
                $row['cantidad'] = $detalle->recibido;
                $results[$cont] = $row;
                $cont++;
            }
        }
        Log::channel('testing')->info('Log', ['empaquetarDatos', $results]);
        return $results;
    }
}
