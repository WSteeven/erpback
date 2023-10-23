<?php

namespace Src\App\Bodega;

use App\Http\Resources\PedidoResource;
use App\Models\Devolucion;
use App\Models\EstadoTransaccion;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Config\Autorizaciones;
use Src\Config\EstadosTransacciones;

class DevolucionService
{

    public function __construct()
    {
    }

    public static function filtrarDevoluciones($request)
    {
        $results = [];
        switch ($request->estado) {
            case 'PENDIENTE':
                if (auth()->user()->hasRole([User::ROL_BODEGA, User::ROL_ADMINISTRADOR, User::ROL_ACTIVOS_FIJOS, User::ROL_GERENTE, User::ROL_BODEGA_TELCONET])) {
                    $results = Devolucion::where('autorizacion_id', 1)->where('estado', Devolucion::CREADA)->orderBy('updated_at', 'desc')->get();
                } else {
                    $results = Devolucion::where('autorizacion_id', 1)->where('estado', Devolucion::CREADA)
                        ->where(function ($query) {
                            $query->where('solicitante_id', auth()->user()->empleado->id)
                                ->orWhere('per_autoriza_id', auth()->user()->empleado->id);
                        })->orderBy('updated_at', 'desc')->get();
                }
                break;
            case 'APROBADO':
                if (auth()->user()->hasRole([User::ROL_BODEGA, User::ROL_ADMINISTRADOR, User::ROL_ACTIVOS_FIJOS, User::ROL_GERENTE, User::ROL_BODEGA_TELCONET])) {
                    $results = Devolucion::where('autorizacion_id', 2)->where('estado_bodega', EstadoTransaccion::PENDIENTE)->orderBy('updated_at', 'desc')->get();
                } else {
                    $results = Devolucion::where('autorizacion_id', 2)->where('estado_bodega', EstadoTransaccion::PENDIENTE)
                        ->where(function ($query) {
                            $query->where('solicitante_id', auth()->user()->empleado->id)
                                ->orWhere('per_autoriza_id', auth()->user()->empleado->id);
                        })->orderBy('updated_at', 'desc')->get();
                }
                break;
            case 'PARCIAL':
                if (auth()->user()->hasRole([User::ROL_BODEGA, User::ROL_ADMINISTRADOR, User::ROL_ACTIVOS_FIJOS, User::ROL_GERENTE, User::ROL_BODEGA_TELCONET])) {
                    $results = Devolucion::where('autorizacion_id', 2)->where('estado_bodega', EstadoTransaccion::PARCIAL)->orderBy('updated_at', 'desc')->get();
                } else {
                    $results = Devolucion::where('autorizacion_id', 2)->where('estado_bodega', EstadoTransaccion::PARCIAL)
                        ->where(function ($query) {
                            $query->where('solicitante_id', auth()->user()->empleado->id)
                                ->orWhere('per_autoriza_id', auth()->user()->empleado->id);
                        })->orderBy('updated_at', 'desc')->get();
                }
                break;
            case 'CANCELADO':
                if (auth()->user()->hasRole([User::ROL_BODEGA, User::ROL_ADMINISTRADOR, User::ROL_ACTIVOS_FIJOS, User::ROL_GERENTE, User::ROL_BODEGA_TELCONET])) {
                    $results = Devolucion::where('autorizacion_id', 3)->orWhere('estado_bodega', EstadoTransaccion::ANULADA)->orderBy('updated_at', 'desc')->get();
                } else {
                    $results = Devolucion::where('autorizacion_id', 3)->orWhere('estado_bodega', EstadoTransaccion::ANULADA)
                        ->where(function ($query) {
                            $query->where('solicitante_id', auth()->user()->empleado->id)
                                ->orWhere('per_autoriza_id', auth()->user()->empleado->id);
                        })->orderBy('updated_at', 'desc')->get();
                }
                break;
            case 'COMPLETA':
                if (auth()->user()->hasRole([User::ROL_BODEGA, User::ROL_ADMINISTRADOR, User::ROL_ACTIVOS_FIJOS, User::ROL_GERENTE, User::ROL_BODEGA_TELCONET])) {
                    $results = Devolucion::where('estado_bodega', EstadoTransaccion::COMPLETA)->orderBy('updated_at', 'desc')->get();
                } else {
                    $results = Devolucion::where('estado_bodega', EstadoTransaccion::COMPLETA)
                        ->where(function ($query) {
                            $query->where('solicitante_id', auth()->user()->empleado->id)
                                ->orWhere('per_autoriza_id', auth()->user()->empleado->id);
                        })->orderBy('updated_at', 'desc')->get();
                }
                break;
            default:
                $results = Devolucion::where('solicitante_id', auth()->user()->empleado->id)->orWhere('per_autoriza_id', auth()->user()->empleado->id)->orderBy('updated_at', 'desc')->get();
        }
        return $results;
    }

    public static function verificarItemsDevolucion($detalleDevolucionProducto){
        $resultados = DB::select('select count(*) as cantidad from detalle_devolucion_producto dpp where dpp.devolucion_id=' . $detalleDevolucionProducto->devolucion_id . ' and dpp.cantidad!=dpp.devuelto');
        $devolucion =Devolucion::find($detalleDevolucionProducto->devolucion_id);

        if ($resultados[0]->cantidad > 0) {
            // Log::channel('testing')->info('Log', ['todavia no esta completada']);
            $devolucion->update(['estado_bodega' => EstadoTransaccion::PARCIAL]);
        } else {
            // Log::channel('testing')->info('Log', ['la devolucion esta completada!!']);
            $devolucion->update(['estado_bodega' => EstadoTransaccion::COMPLETA]);
        }
    }
}
