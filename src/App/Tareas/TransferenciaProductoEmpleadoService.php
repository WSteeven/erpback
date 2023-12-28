<?php

namespace Src\App\Tareas;

use App\Models\Autorizacion;
use App\Models\Tareas\TransferenciaProductoEmpleado;
use App\Models\User;

class TransferenciaProductoEmpleadoService
{
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
}
