<?php

namespace Src\App;

use App\Models\Autorizacion;
use App\Models\EstadoTransaccion;
use App\Models\TransaccionBodega;
use App\Models\User;

class TransaccionBodegaEgresoService
{

    public function obtenerTransaccionesDeEgresosConPaginacion(int $page, int $offset)
    {
    }
    public function obtenerTransaccionesDeEgresosSinPaginacion(String $estado)
    {
        $results = [];
        if (auth()->user()->hasRole(User::ROL_COORDINADOR)) {
            switch ($estado) {
                case 'ESPERA':
                    $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                        ->where('solicitante_id', auth()->user()->empleado->id)
                        ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                        ->join('subtipos_transacciones', 'subtipo_id', '=', 'subtipos_transacciones.id')
                        ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                        ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                        // ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                        // ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                        // ->whereNotBetween('estados_transacciones_bodega.nombre', [EstadoTransaccion::PENDIENTE, EstadoTransaccion::COMPLETA, EstadoTransaccion::PARCIAL])
                        ->join('tiempo_autorizacion_transaccion', 'transacciones_bodega.id', '=', 'tiempo_autorizacion_transaccion.transaccion_id')
                        ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', '=', 'autorizaciones.id')
                        ->where('autorizaciones.nombre', '=', Autorizacion::PENDIENTE)
                        ->ignoreRequest(['estado'])->filter()->get();
                case 'PARCIAL':
                    $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                        ->where('solicitante_id', auth()->user()->empleado->id)
                        ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                        ->join('subtipos_transacciones', 'subtipo_id', '=', 'subtipos_transacciones.id')
                        ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                        ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                        ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                        ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                        ->where('estados_transacciones_bodega.nombre', $estado)
                        ->join('tiempo_autorizacion_transaccion', 'transacciones_bodega.id', '=', 'tiempo_autorizacion_transaccion.transaccion_id')
                        ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', '=', 'autorizaciones.id')
                        ->where('autorizaciones.nombre', '=', Autorizacion::APROBADO)
                        ->ignoreRequest(['estado'])->filter()->get();
                case 'PENDIENTE':
                    $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                        ->where('solicitante_id', auth()->user()->empleado->id)
                        ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                        ->join('subtipos_transacciones', 'subtipo_id', '=', 'subtipos_transacciones.id')
                        ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                        ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                        ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                        ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                        ->where('estados_transacciones_bodega.nombre', $estado)
                        ->join('tiempo_autorizacion_transaccion', 'transacciones_bodega.id', '=', 'tiempo_autorizacion_transaccion.transaccion_id')
                        ->join('autorizaciones', 'tiempo_autorizacion_transaccion.autorizacion_id', '=', 'autorizaciones.id')
                        ->where('autorizaciones.nombre', '=', Autorizacion::APROBADO)
                        ->ignoreRequest(['estado'])->filter()->get();
                case 'COMPLETA':
                    $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                        ->where('solicitante_id', auth()->user()->empleado->id)
                        ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                        ->join('subtipos_transacciones', 'subtipo_id', '=', 'subtipos_transacciones.id')
                        ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                        ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                        ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                        ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                        ->where('estados_transacciones_bodega.nombre', $estado)
                        ->ignoreRequest(['estado'])->filter()->get();
                default: //todo
                    $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                        ->where('solicitante_id', auth()->user()->empleado->id)
                        ->orWhere('per_autoriza_id', auth()->user()->empleado->id)
                        ->join('subtipos_transacciones', 'subtipo_id', '=', 'subtipos_transacciones.id')
                        ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                        ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                        ->ignoreRequest(['estado'])->filter()->get();
            }
        }
        if (auth()->user()->hasRole(User::ROL_BODEGA)) {
            $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "subtipo_id", "tarea_id", "subtarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id",])
                ->join('subtipos_transacciones', 'subtipo_id', '=', 'subtipos_transacciones.id')
                ->join('tipos_transacciones', 'subtipos_transacciones.tipo_transaccion_id', '=', 'tipos_transacciones.id')
                ->where('tipos_transacciones.tipo', '=', 'EGRESO')
                ->join('tiempo_estado_transaccion', 'transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
                ->where('estados_transacciones_bodega.nombre', $estado)
                ->ignoreRequest(['estado'])->filter()->get();
        }
    }
}
