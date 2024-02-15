<?php

namespace Src\App;

use App\Models\Autorizacion;
use App\Models\DetalleDevolucionProducto;
use App\Models\Devolucion;
use App\Models\EstadoTransaccion;
use App\Models\MaterialEmpleado;
use App\Models\MaterialEmpleadoTarea;
use App\Models\Motivo;
use App\Models\TipoTransaccion;
use App\Models\TransaccionBodega;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\App\Bodega\DevolucionService;

class TransaccionBodegaIngresoService
{

    /**************************************************************************************************
     * Filtros con paginación, DESUSO, BORRAR
     * ************************************************************************************************/
    public static function filtrarTransaccionesIngresoEmpleadoConPaginacion($tipo, $estado, $offset)
    {
        $results = [];
        switch ($estado) {
            case 'ESPERA':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id",  "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('tipos_transacciones', 'transacciones_bodega.tipo_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.nombre', '=', $tipo)
                    ->join('tiempo_autorizacion_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_autorizacion_transaccion.transaccion_id')
                            ->where('tiempo_autorizacion_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_autorizacion_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_autorizacion_transaccion.autorizacion_id', DB::raw('(select id from autorizaciones where autorizaciones.nombre ="PENDIENTE")'));
                    })
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::PENDIENTE)
                    ->simplePaginate($offset);
                return $results;
            case 'PARCIAL':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id",  "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('tipos_transacciones', 'transacciones_bodega.tipo_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.nombre', '=', $tipo)
                    ->join('tiempo_estado_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                            ->where('tiempo_estado_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_estado_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_estado_transaccion.estado_id', DB::raw('(select id from estados_transacciones_bodega where estados_transacciones_bodega.nombre ="PARCIAL")'));
                    })
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::PARCIAL)
                    ->join('tiempo_autorizacion_transaccion', 'transacciones_bodega.id', 'tiempo_autorizacion_transaccion.transaccion_id')
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->simplePaginate($offset);
                return $results;
            case 'PENDIENTE':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id",  "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('tipos_transacciones', 'transacciones_bodega.tipo_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.nombre', '=', $tipo)
                    // ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                    ->join('tiempo_estado_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                            ->where('tiempo_estado_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_estado_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_estado_transaccion.estado_id', DB::raw('(select id from estados_transacciones_bodega where estados_transacciones_bodega.nombre ="PENDIENTE")'));
                    })
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::PENDIENTE)
                    ->join('tiempo_autorizacion_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_autorizacion_transaccion.transaccion_id')
                            ->where('tiempo_autorizacion_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_autorizacion_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_autorizacion_transaccion.autorizacion_id', DB::raw('(select id from autorizaciones where autorizaciones.nombre ="APROBADO")'));
                    })
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->simplePaginate($offset);
                return $results;
            case 'COMPLETA':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id",  "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('tipos_transacciones', 'transacciones_bodega.tipo_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.nombre', '=', $tipo)
                    ->join('tiempo_estado_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                            ->where('tiempo_estado_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_estado_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_estado_transaccion.estado_id', DB::raw('(select id from estados_transacciones_bodega where estados_transacciones_bodega.nombre ="PENDIENTE")'));
                    })
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::COMPLETA)
                    ->join('tiempo_autorizacion_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_autorizacion_transaccion.transaccion_id')
                            ->where('tiempo_autorizacion_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_autorizacion_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_autorizacion_transaccion.autorizacion_id', DB::raw('(select id from autorizaciones where autorizaciones.nombre ="APROBADO")'));
                    })
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->simplePaginate($offset);
                return $results;
            default:
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id",  "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('tipos_transacciones', 'transacciones_bodega.tipo_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.nombre', '=', $tipo)
                    ->simplePaginate($offset);
                return $results;
        }
    }
    public static function filtrarTransaccionesIngresoCoordinadorConPaginacion($tipo, $estado, $offset)
    {
        $results = [];
        switch ($estado) {
            case 'ESPERA':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id",  "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('tipos_transacciones', 'transacciones_bodega.tipo_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.nombre', '=', $tipo)
                    ->join('tiempo_autorizacion_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_autorizacion_transaccion.transaccion_id')
                            ->where('tiempo_autorizacion_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_autorizacion_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_autorizacion_transaccion.autorizacion_id', DB::raw('(select id from autorizaciones where autorizaciones.nombre ="PENDIENTE")'));
                    })
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::PENDIENTE)
                    ->simplePaginate($offset);
                return $results;
            case 'PARCIAL':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id",  "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('tipos_transacciones', 'transacciones_bodega.tipo_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.nombre', '=', $tipo)
                    ->join('tiempo_estado_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                            ->where('tiempo_estado_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_estado_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_estado_transaccion.estado_id', DB::raw('(select id from estados_transacciones_bodega where estados_transacciones_bodega.nombre ="PARCIAL")'));
                    })
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::PARCIAL)
                    ->join('tiempo_autorizacion_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_autorizacion_transaccion.transaccion_id')
                            ->where('tiempo_autorizacion_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_autorizacion_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_autorizacion_transaccion.autorizacion_id', DB::raw('(select id from autorizaciones where autorizaciones.nombre ="APROBADO")'));
                    })
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->simplePaginate($offset);
                return $results;
            case 'PENDIENTE':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id",  "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('tipos_transacciones', 'transacciones_bodega.tipo_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.nombre', '=', $tipo)
                    ->join('tiempo_estado_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                            ->where('tiempo_estado_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_estado_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_estado_transaccion.estado_id', DB::raw('(select id from estados_transacciones_bodega where estados_transacciones_bodega.nombre ="PENDIENTE")'));
                    })
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::PENDIENTE)
                    ->join('tiempo_autorizacion_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_autorizacion_transaccion.transaccion_id')
                            ->where('tiempo_autorizacion_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_autorizacion_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_autorizacion_transaccion.autorizacion_id', DB::raw('(select id from autorizaciones where autorizaciones.nombre ="APROBADO")'));
                    })
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->simplePaginate($offset);
                return $results;
            case 'COMPLETA':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id",  "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('tipos_transacciones', 'transacciones_bodega.tipo_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.nombre', '=', $tipo)
                    ->join('tiempo_estado_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                            ->where('tiempo_estado_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_estado_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_estado_transaccion.estado_id', DB::raw('(select id from estados_transacciones_bodega where estados_transacciones_bodega.nombre ="COMPLETA")'));
                    })
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::COMPLETA)
                    ->join('tiempo_autorizacion_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_autorizacion_transaccion.transaccion_id')
                            ->where('tiempo_autorizacion_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_autorizacion_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_autorizacion_transaccion.autorizacion_id', DB::raw('(select id from autorizaciones where autorizaciones.nombre ="APROBADO")'));
                    })
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->simplePaginate($offset);
                return $results;
            default:
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id",  "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('tipos_transacciones', 'transacciones_bodega.tipo_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.nombre', '=', $tipo)
                    ->simplePaginate($offset);
                return $results;
        }
    }
    public static function filtrarTransaccionesIngresoBodegueroConPaginacion($tipo, $estado, $offset)
    {
        $tipoTransaccion = TipoTransaccion::where('nombre', $tipo)->first();
        $results = [];
        switch ($estado) {
            case 'PARCIAL':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id",  "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->join('motivos', 'transacciones_bodega.motivo_id', 'motivos.id')
                    ->where('motivos.tipo_transaccion_id', '=', $tipoTransaccion->id)
                    ->join('tiempo_estado_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                            ->where('tiempo_estado_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_estado_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_estado_transaccion.estado_id', DB::raw('(select id from estados_transacciones_bodega where estados_transacciones_bodega.nombre ="PARCIAL")'));
                    })
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::PARCIAL)
                    ->simplePaginate($offset);
                return $results;
            case 'PENDIENTE':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id",  "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->join('motivos', 'transacciones_bodega.motivo_id', 'motivos.id')
                    ->where('motivos.tipo_transaccion_id', '=', $tipoTransaccion->id)
                    ->join('tiempo_estado_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                            ->where('tiempo_estado_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_estado_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_estado_transaccion.estado_id', DB::raw('(select id from estados_transacciones_bodega where estados_transacciones_bodega.nombre ="PENDIENTE")'));
                    })
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::PENDIENTE)
                    ->simplePaginate($offset);
                return $results;
            case 'COMPLETA':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id",  "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->join('motivos', 'transacciones_bodega.motivo_id', 'motivos.id')
                    ->where('motivos.tipo_transaccion_id', '=', $tipoTransaccion->id)
                    ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::COMPLETA)
                    ->simplePaginate($offset);
                return $results;
            default:
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->join('motivos', 'transacciones_bodega.motivo_id', 'motivos.id')
                    ->where('motivos.tipo_transaccion_id', '=', $tipoTransaccion->id)
                    ->simplePaginate($offset);
                return $results;
        }
    }

    /*********************************************************************************************
    Filtros sin paginación
     ***********************************************************************************************/
    /**
     * DESUSO
     */
    public static function filtrarTransaccionesIngresoEmpleadoSinPaginacion($tipo, $estado)
    {
        $results = [];
        switch ($estado) {
            case 'ESPERA':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id"])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('motivos', 'motivo_id', '=', 'motivos.id')
                    ->join('tipos_transacciones', 'motivos.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.nombre', '=', $tipo)
                    ->join('tiempo_autorizacion_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_autorizacion_transaccion.transaccion_id')
                            ->where('tiempo_autorizacion_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_autorizacion_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_autorizacion_transaccion.autorizacion_id', DB::raw('(select id from autorizaciones where autorizaciones.nombre ="PENDIENTE")'));
                    })
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::PENDIENTE)
                    ->filter()->get();
                return $results;
            case 'PARCIAL':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id",  "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('motivos', 'motivo_id', '=', 'motivos.id')
                    ->join('tipos_transacciones', 'motivos.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.nombre', '=', $tipo)
                    ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::PARCIAL)
                    ->join('tiempo_autorizacion_transaccion', 'transacciones_bodega.id', 'tiempo_autorizacion_transaccion.transaccion_id')
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->filter()->get();
                return $results;
            case 'PENDIENTE':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id",  "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('motivos', 'motivo_id', '=', 'motivos.id')
                    ->join('tipos_transacciones', 'motivos.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.nombre', '=', $tipo)
                    // ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                    ->join('tiempo_estado_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                            ->where('tiempo_estado_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_estado_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_estado_transaccion.estado_id', DB::raw('(select id from estados_transacciones_bodega where estados_transacciones_bodega.nombre ="PENDIENTE")'));
                    })
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::PENDIENTE)
                    ->join('tiempo_autorizacion_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_autorizacion_transaccion.transaccion_id')
                            ->where('tiempo_autorizacion_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_autorizacion_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_autorizacion_transaccion.autorizacion_id', DB::raw('(select id from autorizaciones where autorizaciones.nombre ="APROBADO")'));
                    })
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->filter()->get();
                return $results;
            case 'COMPLETA':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id",  "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('motivos', 'motivo_id', '=', 'motivos.id')
                    ->join('tipos_transacciones', 'motivos.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.nombre', '=', $tipo)
                    ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::COMPLETA)
                    ->join('tiempo_autorizacion_transaccion', 'transacciones_bodega.id', 'tiempo_autorizacion_transaccion.transaccion_id')
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->filter()->get();
                return $results;
            default:
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id",  "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('motivos', 'motivo_id', '=', 'motivos.id')
                    ->join('tipos_transacciones', 'motivos.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.nombre', '=', $tipo)
                    ->filter()->get();
                return $results;
        }
    }
    /**
     * DESUSO
     */
    public static function filtrarTransaccionesIngresoCoordinadorSinPaginacion($tipo, $estado)
    {
        $results = [];
        switch ($estado) {
            case 'ESPERA':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id"])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('motivos', 'motivo_id', '=', 'motivos.id')
                    ->join('tipos_transacciones', 'motivos.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.nombre', '=', $tipo)
                    ->join('tiempo_autorizacion_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_autorizacion_transaccion.transaccion_id')
                            ->where('tiempo_autorizacion_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_autorizacion_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_autorizacion_transaccion.autorizacion_id', DB::raw('(select id from autorizaciones where autorizaciones.nombre ="PENDIENTE")'));
                    })
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::PENDIENTE)
                    ->filter()->get();
                return $results;
            case 'PARCIAL':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id",  "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('motivos', 'motivo_id', '=', 'motivos.id')
                    ->join('tipos_transacciones', 'motivos.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.nombre', '=', $tipo)
                    ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::PARCIAL)
                    ->join('tiempo_autorizacion_transaccion', 'transacciones_bodega.id', 'tiempo_autorizacion_transaccion.transaccion_id')
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->filter()->get();
                return $results;
            case 'PENDIENTE':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id",  "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('motivos', 'motivo_id', '=', 'motivos.id')
                    ->join('tipos_transacciones', 'motivos.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.nombre', '=', $tipo)
                    ->join('tiempo_estado_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                            ->where('tiempo_estado_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_estado_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_estado_transaccion.estado_id', DB::raw('(select id from estados_transacciones_bodega where estados_transacciones_bodega.nombre ="PENDIENTE")'));
                    })
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::PENDIENTE)
                    ->join('tiempo_autorizacion_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_autorizacion_transaccion.transaccion_id')
                            ->where('tiempo_autorizacion_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_autorizacion_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_autorizacion_transaccion.autorizacion_id', DB::raw('(select id from autorizaciones where autorizaciones.nombre ="APROBADO")'));
                    })
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->filter()->get();
                return $results;
            case 'COMPLETA':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id",  "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('motivos', 'motivo_id', '=', 'motivos.id')
                    ->join('tipos_transacciones', 'motivos.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.nombre', '=', $tipo)
                    ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::COMPLETA)
                    ->join('tiempo_autorizacion_transaccion', 'transacciones_bodega.id', 'tiempo_autorizacion_transaccion.transaccion_id')
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->filter()->get();
                return $results;
            default:
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id",  "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('motivos', 'motivo_id', '=', 'motivos.id')
                    ->join('tipos_transacciones', 'motivos.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.nombre', '=', $tipo)
                    ->filter()->get();
                return $results;
        }
    }
    /**
     * Filtra las transacciones de ingreso segun el estado recibido
     * @param $estado String, estado recibido para el filtrado
     * @param $tipo String, siempre es INGRESO
     * @return $results listado de transacciones filtradas.
     */
    public static function filtrarTransaccionesIngresoBodegueroSinPaginacion($tipo, $estado)
    {
        $results = [];
        switch ($estado) {
            case 'ESPERA':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "autorizacion_id", "estado_id", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id"])
                    ->join('motivos', 'motivo_id', '=', 'motivos.id')
                    ->join('tipos_transacciones', 'motivos.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.nombre', '=', $tipo)
                    ->join('autorizaciones', 'autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::PENDIENTE)
                    ->ignoreRequest(['estado'])
                    ->filter()->get();
                return $results;
            case 'PARCIAL':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "autorizacion_id", "estado_id", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id",  "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->join('motivos', 'motivo_id', '=', 'motivos.id')
                    ->join('tipos_transacciones', 'motivos.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.nombre', '=', $tipo)
                    ->join('estados_transacciones_bodega', 'estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::PARCIAL)
                    ->join('autorizaciones', 'autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->ignoreRequest(['estado'])
                    ->filter()->get();
                return $results;
            case 'PENDIENTE':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "autorizacion_id", "estado_id", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id",  "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->join('motivos', 'motivo_id', '=', 'motivos.id')
                    ->join('tipos_transacciones', 'motivos.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.nombre', '=', $tipo)
                    ->join('estados_transacciones_bodega', 'estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::PENDIENTE)
                    ->join('autorizaciones', 'autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->ignoreRequest(['estado'])
                    ->filter()->get();
                return $results;
            case 'COMPLETA':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "autorizacion_id", "estado_id", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id",  "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->join('motivos', 'motivo_id', '=', 'motivos.id')
                    ->join('tipos_transacciones', 'motivos.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.nombre', '=', $tipo)
                    ->join('estados_transacciones_bodega', 'estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::COMPLETA)
                    ->join('autorizaciones', 'autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->ignoreRequest(['estado'])
                    ->filter()->get();
                return $results;
            default:
                // Log::channel('testing')->info('Log', ['Estoy en el default y el estado es', $estado]);
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "autorizacion_id", "estado_id", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id",  "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->join('motivos', 'motivo_id', '=', 'motivos.id')
                    ->join('tipos_transacciones', 'motivos.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.nombre', '=', $tipo)
                    ->ignoreRequest(['estado'])
                    ->filter()->get();
                return $results;
        }
    }


    public static function filtrarIngresoPorTipoFiltro($request)
    {
        $tipoTransaccion = TipoTransaccion::where('nombre', TipoTransaccion::INGRESO)->first();
        $motivos = Motivo::where('tipo_transaccion_id', $tipoTransaccion->id)->get('id');
        $results = [];
        switch ($request->tipo) {
            case 0: //persona que solicita el ingreso
                $results = TransaccionBodega::whereIn('motivo_id', $motivos)->where('solicitante_id', $request->solicitante)
                    ->whereBetween(
                        'created_at',
                        [
                            date('Y-m-d', strtotime($request->fecha_inicio)),
                            $request->fecha_fin ? date('Y-m-d', strtotime($request->fecha_fin)) : date("Y-m-d h:i:s")
                        ]
                    )->orderBy('id', 'desc')->get();
                break;
            case 1: //bodeguero
                $results = TransaccionBodega::whereIn('motivo_id', $motivos)->where('per_atiende_id', $request->per_atiende)
                    ->whereBetween(
                        'created_at',
                        [
                            date('Y-m-d', strtotime($request->fecha_inicio)), //start date
                            $request->fecha_fin ? date('Y-m-d', strtotime($request->fecha_fin)) : date("Y-m-d h:i:s") //end date
                        ]
                    )->orderBy('id', 'desc')->get(); //sort descending
                break;
            case 2: //motivos
                $results = TransaccionBodega::where('motivo_id', $request->motivo)
                    ->whereBetween(
                        'created_at',
                        [
                            date('Y-m-d', strtotime($request->fecha_inicio)),
                            $request->fecha_fin ? date('Y-m-d', strtotime($request->fecha_fin)) : date("Y-m-d h:i:s")
                        ]
                    )->orderBy('id', 'desc')->get();
                break;
            case 3: //bodega o sucursal
                $request->sucursal != 0 ? $results = TransaccionBodega::whereIn('motivo_id', $motivos)->where('sucursal_id', $request->sucursal)
                    ->whereBetween(
                        'created_at',
                        [
                            date('Y-m-d', strtotime($request->fecha_inicio)),
                            $request->fecha_fin ? date('Y-m-d', strtotime($request->fecha_fin)) : date("Y-m-d h:i:s")
                        ]
                    )->orderBy('id', 'desc')->get() : $results = TransaccionBodega::whereIn('motivo_id', $motivos)->whereBetween(
                        'created_at',
                        [
                            date('Y-m-d', strtotime($request->fecha_inicio)),
                            $request->fecha_fin ? date('Y-m-d', strtotime($request->fecha_fin)) : date("Y-m-d h:i:s")
                        ]
                    )->orderBy('id', 'desc')->get();
                break;
            case 4: // devolucion
                $results = TransaccionBodega::whereIn('motivo_id', $motivos)->where('devolucion_id', $request->devolucion)
                    ->whereBetween(
                        'created_at',
                        [
                            date('Y-m-d', strtotime($request->fecha_inicio)),
                            $request->fecha_fin ? date('Y-m-d', strtotime($request->fecha_fin)) : date("Y-m-d h:i:s")
                        ]
                    )->orderBy('id', 'desc')->get();
                break;
            case 5: //tarea
                $results = TransaccionBodega::whereIn('motivo_id', $motivos)->where('devolucion_id', $request->tarea)
                    ->whereBetween(
                        'created_at',
                        [
                            date('Y-m-d', strtotime($request->fecha_inicio)),
                            $request->fecha_fin ? date('Y-m-d', strtotime($request->fecha_fin)) : date("Y-m-d h:i:s")
                        ]
                    )->orderBy('id', 'desc')->get();
                break;
            case 6: //transferencia
                $results = TransaccionBodega::whereIn('motivo_id', $motivos)->where('transferencia_id', $request->transferencia)
                    ->whereBetween(
                        'created_at',
                        [
                            date('Y-m-d', strtotime($request->fecha_inicio)),
                            $request->fecha_fin ? date('Y-m-d', strtotime($request->fecha_fin)) : date("Y-m-d h:i:s")
                        ]
                    )->orderBy('id', 'desc')->get();
                break;
            default:
                $results = TransaccionBodega::whereIn('motivo_id', $motivos)->orderBy('id', 'desc')->get(); // todos los ingresos
                break;
        }
        return $results;
    }

    /**
     * La función "descontarMaterialesAsignados" se utiliza para actualizar el stock de materiales 
     * de un empleado luego de devolver a bodega ciertos materiales asignados a una transacción.
     * 
     * @param listado Una matriz que contiene los detalles del material que se va a deducir, como la
     * cantidad.
     * @param transaccion El parámetro "transacción" es un objeto que representa una transacción.
     * Contiene información como el ID de la tarea, el ID del solicitante y otros detalles relacionados
     * con la transacción.
     * @param detalle El parámetro `` es un objeto que representa los detalles de un producto.
     * Probablemente contenga información como el ID del producto, el nombre, la descripción y otros
     * detalles relevantes.
     */
    public function descontarMaterialesAsignados($listado, $transaccion, $detalle)
    {
        if ($transaccion->tarea_id) {
            if ($transaccion->devolucion_id) {
                $devolucion = Devolucion::find($transaccion->devolucion_id);
                MaterialEmpleadoTarea::descargarMaterialEmpleadoTarea($detalle->id, $transaccion->solicitante_id, $transaccion->tarea_id, $listado['cantidad'], $devolucion->cliente_id);
            } else MaterialEmpleadoTarea::descargarMaterialEmpleadoTarea($detalle->id, $transaccion->solicitante_id, $transaccion->tarea_id, $listado['cantidad'], $transaccion->cliente_id);
        } else { // Devolucion de stock personal
            if ($transaccion->devolucion_id) {
                $devolucion = Devolucion::find($transaccion->devolucion_id);
                MaterialEmpleado::descargarMaterialEmpleado($detalle->id, $transaccion->solicitante_id, $listado['cantidad'], $devolucion->cliente_id, $transaccion->cliente_id);
            } else {
                MaterialEmpleado::descargarMaterialEmpleado($detalle->id, $transaccion->solicitante_id, $listado['cantidad'], $transaccion->cliente_id, $transaccion->cliente_id);
            }
        }
    }

    public static function actualizarDevolucion($transaccion, $detalle, $cantidad)
    {
        $itemDevolucion  = DetalleDevolucionProducto::where('devolucion_id', $transaccion->devolucion_id)->where('detalle_id', $detalle->id)->first();
        if ($itemDevolucion) {
            $itemDevolucion->devuelto += $cantidad;
            $itemDevolucion->save();
        } else {
            $itemDevolucion = DetalleDevolucionProducto::create([
                'devolucion_id' => $transaccion->devolucion_id,
                'detalle_id' => $detalle->id,
                'cantidad' => $cantidad,
                'devuelto' => $cantidad,
            ]);
        }
        //aquí se verifica si se completaron los items de la devolución y se actualiza con parcial o completado según corresponda.
        DevolucionService::verificarItemsDevolucion($itemDevolucion);
    }

    public static function anularIngresoDevolucion($transaccion, $devolucion_id, $detalle_id, $cantidad)
    {
        $devolucion = Devolucion::find($devolucion_id);
        if ($transaccion->tarea_id) {
            MaterialEmpleadoTarea::cargarMaterialEmpleadoTareaPorAnulacionDevolucion($detalle_id, $devolucion->solicitante_id, $transaccion->tarea_id, $cantidad, $devolucion->cliente_id, $transaccion->proyecto_id, $transaccion->etapa_id);
        } else {
            MaterialEmpleado::cargarMaterialEmpleadoPorAnulacionDevolucion($detalle_id, $devolucion->solicitante_id, $cantidad, $devolucion->cliente_id);
        }
        $item = DetalleDevolucionProducto::where('devolucion_id', $devolucion_id)->where('detalle_id', $detalle_id)->first();
        if ($item) {
            $item->devuelto -= $cantidad;
            $item->save();
        } else throw new Exception('Ha ocurrido un error al intentar restar de la devolucion el item ' . $detalle_id);

        DevolucionService::verificarItemsDevolucion($item);
    }
}
