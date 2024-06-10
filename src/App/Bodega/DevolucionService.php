<?php

namespace Src\App\Bodega;

use App\Http\Resources\PedidoResource;
use App\Models\Autorizacion;
use App\Models\DetalleDevolucionProducto;
use App\Models\Devolucion;
use App\Models\EstadoTransaccion;
use App\Models\Pedido;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Config\Autorizaciones;
use Src\Config\EstadosTransacciones;

class DevolucionService
{

    public function __construct()
    {
    }

    public static function listar($request)
    {
        $results = [];
        try {
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }
        if ($request->estado) {
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
        } else {
            $results = Devolucion::when($request->fecha_inicio, function ($q) use ($request) {
                $q->where('created_at', '>=', $request->fecha_inicio);
            })
                ->when($request->fecha_fin, function ($q) use ($request) {
                    $q->where('created_at', '<=', $request->fecha_fin);
                })
                ->orderBy('updated_at', 'desc')->get();
        }
        return $results;
    }

    /**
     * La función "verificarItemsDevolucion" comprueba si hay artículos en una devolución que no hayan
     * sido devueltos en su totalidad y actualiza el estado de la devolución en consecuencia.
     * 
     * @param detalleDevolucionProducto El parámetro "detalleDevolucionProducto" es un objeto que
     * representa un detalle específico de la devolución de un producto. Probablemente contenga
     * información como el ID de devolución, el ID del producto, la cantidad devuelta y la cantidad ya
     * procesada.
     */
    public static function verificarItemsDevolucion($detalleDevolucionProducto)
    {
        $resultados = DB::select('select count(*) as cantidad from detalle_devolucion_producto dpp where dpp.devolucion_id=' . $detalleDevolucionProducto->devolucion_id . ' and dpp.cantidad!=dpp.devuelto');
        $devolucion = Devolucion::find($detalleDevolucionProducto->devolucion_id);
        $detallesSinDevolver = $devolucion->detalles()->where('devuelto', 0)->count();

        if ($detallesSinDevolver === $devolucion->detalles()->count()) $devolucion->update(['estado_bodega' => EstadoTransaccion::PENDIENTE]);
        else {
            if ($resultados[0]->cantidad > 0) $devolucion->update(['estado_bodega' => EstadoTransaccion::PARCIAL]);
            else $devolucion->update(['estado_bodega' => EstadoTransaccion::COMPLETA]);
        }
    }

    public static function crearPedidoAutomatico($devolucion)
    {
        try {
            DB::beginTransaction();
            $carbon = new Carbon();
            $fecha_limite = $carbon->now()->addDays(2);
            $pedido = Pedido::create([
                'justificacion' => $devolucion->justificacion,
                'fecha_limite' => date('Y-m-d', strtotime($fecha_limite)),
                'solicitante_id' => $devolucion->solicitante_id,
                'responsable_id' => $devolucion->solicitante_id,
                'autorizacion_id' => $devolucion->autorizacion_id,
                'per_autoriza_id' => $devolucion->per_autoriza_id,
                'tarea_id' => $devolucion->tarea_id ? $devolucion->tarea_id : null,
                'sucursal_id' => $devolucion->sucursal_id,
                'estado_id' => EstadosTransacciones::PENDIENTE
            ]);
            $itemsDevolucion = DetalleDevolucionProducto::where('devolucion_id', $devolucion->id)->get();
            foreach ($itemsDevolucion as $item) {
                $pedido->detalles()->attach(
                    $item['detalle_id'],
                    [
                        'cantidad' => $item['cantidad'],
                        'solicitante_id' => $devolucion->solicitante_id
                    ]
                );
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR', $th->getMessage(), $th->getLine()]);
            throw $th;
        }
    }
}
