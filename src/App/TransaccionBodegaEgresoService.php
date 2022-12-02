<?php

namespace Src\App;

use App\Http\Resources\TransaccionBodegaResource;
use App\Models\Autorizacion;
use App\Models\EstadoTransaccion;
use App\Models\TipoTransaccion;
use App\Models\TransaccionBodega;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TransaccionBodegaEgresoService
{

    /* Filtros con paginación */
    public static function filtrarTransaccionesEgresoEmpleadoConPaginacion($tipo, $estado, $offset)
    {
        $tipoTransaccion = TipoTransaccion::where('nombre', $tipo)->first();
        $results = [];
        switch ($estado) {
            case 'ESPERA':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id",  "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->join('motivos', 'transacciones_bodega.motivo_id', '=', 'motivos.id')
                    ->where('motivos.tipo_transaccion_id', '=', $tipoTransaccion->id)
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
                    ->join('motivos', 'transacciones_bodega.motivo_id', '=', 'motivos.id')
                    ->where('motivos.tipo_transaccion_id', '=', $tipoTransaccion->id)
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
                    // ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                    ->join('motivos', 'transacciones_bodega.motivo_id', '=', 'motivos.id')
                    ->where('motivos.tipo_transaccion_id', '=', $tipoTransaccion->id)
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
                    ->join('motivos', 'transacciones_bodega.motivo_id', '=', 'motivos.id')
                    ->where('motivos.tipo_transaccion_id', '=', $tipoTransaccion->id)
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
                    ->join('motivos', 'transacciones_bodega.motivo_id', '=', 'motivos.id')
                    ->where('motivos.tipo_transaccion_id', '=', $tipoTransaccion->id)
                    ->simplePaginate($offset);
                return $results;
        }
    }
    public static function filtrarTransaccionesEgresoCoordinadorConPaginacion($tipo, $estado, $offset)
    {
        $results = [];
        $tipoTransaccion = TipoTransaccion::where('nombre', $tipo)->first();
        switch ($estado) {
            case 'ESPERA':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id",  "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->where('solicitante_id', auth()->user()->empleado->id)
                    ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                    ->orWhere('per_retira_id', auth()->user()->empleado->id)
                    ->whereNull('transacciones_bodega.motivo_id')
                    // ->join('motivos', 'transacciones_bodega.motivo_id', 'motivos.id')
                    // ->where('motivos.tipo_transaccion_id', '=', $tipoTransaccion->id)
                    // ->orWhere('motivos.tipo_transaccion_id', '=', null)
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
                    ->whereNull('transacciones_bodega.motivo_id')
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
                    ->whereNull('transacciones_bodega.motivo_id')
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
                    ->whereNull('transacciones_bodega.motivo_id')
                    // ->join('motivos', 'transacciones_bodega.motivo_id', 'motivos.id')
                    // ->where('motivos.tipo_transaccion_id', '=', $tipoTransaccion->id)
                    ->simplePaginate($offset);
                return $results;
        }
    }
    public static function filtrarTransaccionesEgresoBodegueroConPaginacion($tipo, $estado, $offset)
    {
        $results = [];
        switch ($estado) {
            case 'ESPERA':
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id",  "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->join('tipos_transacciones', 'transacciones_bodega.tipo_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.nombre', '=', $tipo)
                    ->join('tiempo_estado_transaccion', function ($join) {
                        $join->on('transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                            ->where('tiempo_estado_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_estado_transaccion where transaccion_id = transacciones_bodega.id)'))
                            ->where('tiempo_estado_transaccion.estado_id', DB::raw('(select id from estados_transacciones_bodega where estados_transacciones_bodega.nombre ="PENDIENTE")'));
                    })
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
                    ->join('tipos_transacciones', 'transacciones_bodega.tipo_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.nombre', '=', $tipo)
                    ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                    ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                    ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::COMPLETA)
                    ->join('tiempo_autorizacion_transaccion', 'transacciones_bodega.id', 'tiempo_autorizacion_transaccion.transaccion_id')
                    ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', 'autorizaciones.id')
                    ->where('autorizaciones.nombre', Autorizacion::APROBADO)
                    ->simplePaginate($offset);
                return $results;
            default:
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id",  "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->join('tipos_transacciones', 'transacciones_bodega.tipo_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.nombre', '=', $tipo)
                    ->simplePaginate($offset);
                return $results;
        }
    }
    /* Filtros sin paginación */
    public static function filtrarTransaccionesEgresoEmpleadoSinPaginacion($tipo, $estado)
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
    public static function filtrarTransaccionesEgresoCoordinadorSinPaginacion($tipo, $estado)
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
    public static function filtrarTransaccionesEgresoBodegueroSinPaginacion($tipo, $estado)
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
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id",  "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
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
                $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id",  "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
                    ->join('motivos', 'motivo_id', '=', 'motivos.id')
                    ->join('tipos_transacciones', 'motivos.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                    ->where('tipos_transacciones.nombre', '=', $tipo)
                    ->filter()->get();
                return $results;
        }
    }
}
