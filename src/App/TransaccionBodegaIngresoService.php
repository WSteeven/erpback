<?php

namespace Src\App;

use App\Models\Autorizacion;
use App\Models\EstadoTransaccion;
use App\Models\TipoTransaccion;
use App\Models\TransaccionBodega;
use Illuminate\Support\Facades\DB;

class TransaccionBodegaIngresoService
{

    /* Filtros con paginación */
    public static function filtrarTransaccionesIngresoEmpleadoConPaginacion($tipo, $estado, $offset)
    {
        $results = [];
        switch ($estado) {
            case 'ESPERA':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id", "tipo_id", "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
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
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id", "tipo_id", "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
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
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id", "tipo_id", "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
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
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id", "tipo_id", "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
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
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id", "tipo_id", "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
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
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id", "tipo_id", "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
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
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id", "tipo_id", "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
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
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id", "tipo_id", "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
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
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id", "tipo_id", "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
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
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id", "tipo_id", "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
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
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id", "tipo_id", "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->where('transacciones_bodega.tipo_id', '=', $tipoTransaccion->id)
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
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id", "tipo_id", "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->where('transacciones_bodega.tipo_id', '=', $tipoTransaccion->id)
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
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id", "tipo_id", "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->where('transacciones_bodega.tipo_id', '=', $tipoTransaccion->id)
                    ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::COMPLETA)
                    ->simplePaginate($offset);
                return $results;
            default:
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id", "tipo_id", "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->where('transacciones_bodega.tipo_id', '=', $tipoTransaccion->id)
                    ->simplePaginate($offset);
                return $results;
        }
    }
    /* Filtros sin paginación */
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
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id", "tipo_id", "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
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
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id", "tipo_id", "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
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
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id", "tipo_id", "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
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
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id", "tipo_id", "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('motivos', 'motivo_id', '=', 'motivos.id')
                    ->join('tipos_transacciones', 'motivos.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.nombre', '=', $tipo)
                    ->filter()->get();
                return $results;
        }
    }
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
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id", "tipo_id", "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
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
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id", "tipo_id", "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
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
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id", "tipo_id", "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
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
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id", "tipo_id", "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
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
    public static function filtrarTransaccionesIngresoBodegueroSinPaginacion($tipo, $estado)
    {
        $results = [];
        switch ($estado) {
            case 'ESPERA':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id"])
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
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id", "tipo_id", "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
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
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id", "tipo_id", "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
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
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id", "tipo_id", "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
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
                // Log::channel('testing')->info('Log', ['Estoy en el default y el estado es', $estado]);
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id", "tipo_id", "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->join('motivos', 'motivo_id', '=', 'motivos.id')
                    ->join('tipos_transacciones', 'motivos.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.nombre', '=', $tipo)
                    ->filter()->get();
                return $results;
        }
    }
}
