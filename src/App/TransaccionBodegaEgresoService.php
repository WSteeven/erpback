<?php

namespace Src\App;

use App\Http\Resources\TransaccionBodegaResource;
use App\Models\Autorizacion;
use App\Models\DetalleProducto;
use App\Models\EstadoTransaccion;
use App\Models\Motivo;
use App\Models\Subtarea;
use App\Models\TipoTransaccion;
use App\Models\TransaccionBodega;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Type\Integer;
use Src\Config\Autorizaciones;
use Src\Config\ClientesCorporativos;
use Src\Config\EstadosTransacciones;

class TransaccionBodegaEgresoService
{

    public static function listar($request)
    {
        $estado = $request->estado;
        $tipoTransaccion = TipoTransaccion::where('nombre', TipoTransaccion::EGRESO)->first();
        $motivos = Motivo::where('tipo_transaccion_id', $tipoTransaccion->id)->get('id');
        $results = [];
        if (auth()->user()->hasRole([User::ROL_BODEGA, User::ROL_ADMINISTRADOR, User::ROL_CONTABILIDAD, User::ROL_CONSULTA])) { //si es bodeguero
            if ($estado) {
                switch ($estado) {
                    case EstadoTransaccion::PENDIENTE:
                        $results = TransaccionBodega::with('comprobante')->whereIn('motivo_id', $motivos)->whereHas('comprobante', function ($q) {
                            $q->where('firmada', false);
                        })->orderBy('id', 'desc')->get();
                        break;
                    case EstadoTransaccion::PARCIAL:
                        $results = TransaccionBodega::with('comprobante')->whereIn('motivo_id', $motivos)->whereHas('comprobante', function ($q) {
                            $q->where('estado', EstadoTransaccion::PARCIAL);
                        })->orderBy('id', 'desc')->get();
                        break;
                    case EstadoTransaccion::COMPLETA:
                        $results = TransaccionBodega::with('comprobante')->whereIn('motivo_id', $motivos)
                            ->where(function ($query) {
                                $query->whereHas('comprobante', function ($q) {
                                    $q->where('firmada', true)->where('estado', TransaccionBodega::ACEPTADA);
                                })->orWhereDoesntHave('comprobante');
                            })->where('autorizacion_id', Autorizaciones::APROBADO)->orderBy('id', 'desc')->get();
                        break;
                    case 'ANULADA':
                        $results = TransaccionBodega::whereIn('motivo_id', $motivos)->where('estado_id', EstadosTransacciones::ANULADA)->orderBy('id', 'desc')->get();
                        break;
                    default:
                }
            } else {
                $results = TransaccionBodega::whereIn('motivo_id', $motivos)
                    ->when($request->fecha_inicio, function ($q) use ($request) {
                        $q->where('created_at', '>=', $request->fecha_inicio);
                    })
                    ->when($request->fecha_fin, function ($q) use ($request) {
                        $q->where('created_at', '<=', $request->fecha_fin);
                    })
                    ->orderBy('id', 'desc')->get();
            }
        }
        if (auth()->user()->hasRole([User::ROL_BODEGA_TELCONET])) {
            if ($estado) {
                switch ($estado) {
                    case EstadoTransaccion::PENDIENTE:
                        $results = TransaccionBodega::with('comprobante')->whereIn('motivo_id', $motivos)->whereHas('comprobante', function ($q) {
                            $q->where('firmada', false);
                        })->where('cliente_id', ClientesCorporativos::TELCONET)->orderBy('id', 'desc')->get();
                        break;
                    case EstadoTransaccion::PARCIAL:
                        $results = TransaccionBodega::with('comprobante')->whereIn('motivo_id', $motivos)->whereHas('comprobante', function ($q) {
                            $q->where('estado', EstadoTransaccion::PARCIAL);
                        })->where('cliente_id', ClientesCorporativos::TELCONET)->orderBy('id', 'desc')->get();
                        break;
                    case EstadoTransaccion::COMPLETA:
                        $results = TransaccionBodega::with('comprobante')->whereIn('motivo_id', $motivos)
                            ->where(function ($query) {
                                $query->whereHas('comprobante', function ($q) {
                                    $q->where('firmada', true)->where('estado', TransaccionBodega::ACEPTADA);
                                })->orWhereDoesntHave('comprobante');
                            })->where('autorizacion_id', Autorizaciones::APROBADO)->where('cliente_id', ClientesCorporativos::TELCONET)->orderBy('id', 'desc')->get();
                        break;
                    case 'ANULADA':
                        $results = TransaccionBodega::whereIn('motivo_id', $motivos)->where('estado_id', EstadosTransacciones::ANULADA)->where('cliente_id', ClientesCorporativos::TELCONET)->orderBy('id', 'desc')->get();
                        break;
                    default:
                }
            } else
                $results = TransaccionBodega::whereIn('motivo_id', $motivos)
                    ->where('cliente_id', ClientesCorporativos::TELCONET)
                    ->when($request->fecha_inicio, function ($q) use ($request) {
                        $q->where('created_at', '>=', $request->fecha_inicio);
                    })
                    ->when($request->fecha_fin, function ($q) use ($request) {
                        $q->where('created_at', '<=', $request->fecha_fin);
                    })
                    ->orderBy('id', 'desc')->get();
        }
        return $results;
    }

    public static function obtenerTransaccionesPorTarea($tarea_id)
    {
        $results = TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id",  "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
            ->where('tarea_id', '=', $tarea_id)
            ->join('tiempo_estado_transaccion', function ($join) {
                $join->on('transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                    ->where('tiempo_estado_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_estado_transaccion where transaccion_id = transacciones_bodega.id)'))
                    ->where('tiempo_estado_transaccion.estado_id', DB::raw('(select id from estados_transacciones_bodega where estados_transacciones_bodega.nombre ="COMPLETA")'));
            })
            ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
            ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::COMPLETA)
            ->get();

        /* forarch($transaccion) {

        } */

        return $results;
    }


    public function obtenerListadoMaterialesPorTarea($tarea_id)
    {
        // Log::channel('testing')->info('Log', ['resultados de obtener listado materiales por tarea', $results]);
        $results = DB::table('detalle_producto_transaccion')
            ->select(DB::raw('sum(cantidad_inicial) as cantidad', 'detalle_id'), 'detalle_id')
            ->join('transacciones_bodega', 'detalle_producto_transaccion.transaccion_id', '=', 'transacciones_bodega.id')
            ->where('transacciones_bodega.tarea_id', '=', $tarea_id)
            ->groupBy('detalle_id')
            ->get();

        $results  = $results->map(fn ($items) => [
            'cantidad_despachada' => intval($items->cantidad),
            'detalle' => DetalleProducto::find($items->detalle_id)->descripcion,
        ]);

        return $results;
    }


    public function obtenerListadoMaterialesPorTareaSinBobina($tarea_id)
    {
        /* select sum(cantidad_inicial) as cantidad, detalle_id from detalle_producto_transaccion dpt where dpt.transaccion_id in(select id from transacciones_bodega tb where tb.tarea_id=2) and
	dpt.detalle_id in(select id from detalles_productos dp where id not in(select detalle_id from fibras f))
	group by detalle_id
 */
        $results = DB::select('select sum(cantidad_inicial) as cantidad, detalle_id from detalle_producto_transaccion dpt where dpt.transaccion_id in(select id from transacciones_bodega tb where tb.tarea_id=' . $tarea_id . ') and dpt.detalle_id in(select id from detalles_productos dp where id not in(select detalle_id from fibras f)) group by detalle_id');

        // $results = DB::table('detalle_producto_transaccion')
        //     ->select(DB::raw('sum(cantidad_inicial) as cantidad'), 'detalle_id')
        //     ->join('transacciones_bodega', 'detalle_producto_transaccion.transaccion_id', '=', 'transacciones_bodega.id')
        //     ->where('transacciones_bodega.tarea_id', '=', $tarea_id)
        //     ->join('detalles_productos', 'detalle_id', '=', 'detalles_productos.id')
        //     ->join('fibras', 'detalles_productos.id', '=', 'fibras.id')
        //     ->whereNot('detalles_productos.id', '=', 'fibras.detalle_id')
        //     ->groupBy('detalle_id')
        //     ->get();
        $results = collect($results)->map(fn ($items) => [
            'cantidad_despachada' => intval($items->cantidad),
            'detalle' => DetalleProducto::find($items->detalle_id)->descripcion,
        ]);
        return $results;
    }

    /**
     * Esta funcion está en desuso.
     */
    public function obtenerListadoMaterialesPorTareaConBobina($tarea_id)
    {
        // Log::channel('testing')->info('Log', ['resultados de obtener listado materiales por tarea', $results]);
        // $results = DB::table('detalle_producto_transaccion')
        //     ->select(DB::raw('sum(cantidad_inicial) as cantidad', 'detalle_id'), 'detalle_id')
        //     ->join('transacciones_bodega', 'detalle_producto_transaccion.transaccion_id', '=', 'transacciones_bodega.id')
        //     ->where('transacciones_bodega.tarea_id', '=', $tarea_id)
        //     ->join('detalles_productos', 'detalle_producto_transaccion.detalle_id', '=', 'detalles_productos.id')
        //     ->join('fibras', 'detalles_productos.id', '=', 'fibras.id')
        //     ->where('detalles_productos.id', '=', 'fibras.detalle_id')
        //     ->groupBy('detalle_id')
        //     ->get();

        $results = DB::select('select sum(cantidad_inicial) as cantidad, detalle_id from detalle_producto_transaccion dpt where dpt.transaccion_id in(select id from transacciones_bodega tb where tb.tarea_id=' . $tarea_id . ') and dpt.detalle_id in(select id from detalles_productos dp where id in(select detalle_id from fibras f)) group by detalle_id');

        $results = collect($results)->map(fn ($items) => [
            'cantidad_despachada' => intval($items->cantidad),
            'detalle' => DetalleProducto::find($items->detalle_id)->descripcion,
        ]);
        return $results;
    }


    public static function filtrarEgresoPorTipoFiltro($request)
    {
        // Log::channel('testing')->info('Log', ['Request', $request->all()]);
        $tipoTransaccion = TipoTransaccion::where('nombre', TipoTransaccion::EGRESO)->first();
        $motivos = Motivo::where('tipo_transaccion_id', $tipoTransaccion->id)->get('id');
        $results = [];
        switch ($request->tipo) {
            case 0: //persona que solicita el ingreso
                // Log::channel('testing')->info('Log', ['Entró en solicitante']);
                $results = TransaccionBodega::with('comprobante')->whereIn('motivo_id', $motivos)->where('solicitante_id', $request->solicitante)
                    ->whereBetween(
                        'created_at',
                        [
                            date('Y-m-d', strtotime($request->fecha_inicio)),
                            $request->fecha_fin ? date('Y-m-d', strtotime($request->fecha_fin)) : date("Y-m-d h:i:s")
                        ]
                    )->whereHas('comprobante', function ($q) {
                        $q->where('firmada', request('firmada'));
                    })->orderBy('id', 'desc')->get();
                break;
            case 1: //persona que autoriza
                // Log::channel('testing')->info('Log', ['Entró en autorizador']);
                $results = TransaccionBodega::with('comprobante')
                    ->whereIn('motivo_id', $motivos)->where('per_autoriza_id', $request->per_autoriza)
                    ->whereBetween(
                        'created_at',
                        [
                            date('Y-m-d', strtotime($request->fecha_inicio)), //start date
                            $request->fecha_fin ? date('Y-m-d', strtotime($request->fecha_fin)) : date("Y-m-d h:i:s") //end date
                        ]
                    )->whereHas('comprobante', function ($q) {
                        $q->where('firmada', request('firmada'));
                    })->orderBy('id', 'desc')->get();
                break;
            case 2: //persona que retira
                // Log::channel('testing')->info('Log', ['Entró en persona que retira']);
                $results = TransaccionBodega::with('comprobante')
                    ->whereIn('motivo_id', $motivos)->where('per_retira_id', $request->per_retira)
                    ->whereBetween(
                        'created_at',
                        [
                            date('Y-m-d', strtotime($request->fecha_inicio)), //start date
                            $request->fecha_fin ? date('Y-m-d', strtotime($request->fecha_fin)) : date("Y-m-d h:i:s") //end date
                        ]
                    )->whereHas('comprobante', function ($q) {
                        $q->where('firmada', request('firmada'));
                    })->orderBy('id', 'desc')->get();
                break;
            case 3: //persona responsable
                // Log::channel('testing')->info('Log', ['Entró en persona responsable']);
                $results = TransaccionBodega::with('comprobante')
                    ->whereIn('motivo_id', $motivos)->where('responsable_id', $request->responsable)
                    ->whereBetween(
                        'created_at',
                        [
                            date('Y-m-d', strtotime($request->fecha_inicio)), //start date
                            $request->fecha_fin ? date('Y-m-d', strtotime($request->fecha_fin)) : date("Y-m-d h:i:s") //end date
                        ]
                    )->whereHas('comprobante', function ($q) {
                        $q->where('firmada', request('firmada'));
                    })->orderBy('id', 'desc')->get();
                break;
            case 4: //bodeguero
                // Log::channel('testing')->info('Log', ['Entró en bodeguero']);
                $results = TransaccionBodega::with('comprobante')
                    ->whereIn('motivo_id', $motivos)->where('per_atiende_id', $request->per_atiende)
                    ->whereBetween(
                        'created_at',
                        [
                            date('Y-m-d', strtotime($request->fecha_inicio)), //start date
                            $request->fecha_fin ? date('Y-m-d', strtotime($request->fecha_fin)) : date("Y-m-d h:i:s") //end date
                        ]
                    )->whereHas('comprobante', function ($q) {
                        $q->where('firmada', request('firmada'));
                    })->orderBy('id', 'desc')->get();
                break;
            case 5: //motivos
                // Log::channel('testing')->info('Log', ['Entró en motivos']);
                $results = TransaccionBodega::with('comprobante')
                    ->where('motivo_id', $request->motivo)
                    ->whereBetween(
                        'created_at',
                        [
                            date('Y-m-d', strtotime($request->fecha_inicio)),
                            $request->fecha_fin ? date('Y-m-d', strtotime($request->fecha_fin)) : date("Y-m-d h:i:s")
                        ]
                    )->whereHas('comprobante', function ($q) {
                        $q->where('firmada', request('firmada'));
                    })->orderBy('id', 'desc')->get();
                break;
            case 6: //bodega o sucursal
                // Log::channel('testing')->info('Log', ['Entró en bodega o sucursal']);
                if ($request->sucursal != 0)  $results = TransaccionBodega::with('comprobante')
                    ->whereIn('motivo_id', $motivos)->where('sucursal_id', $request->sucursal)
                    ->whereBetween(
                        'created_at',
                        [
                            date('Y-m-d', strtotime($request->fecha_inicio)),
                            $request->fecha_fin ? date('Y-m-d', strtotime($request->fecha_fin)) : date("Y-m-d h:i:s")
                        ]
                    )->whereHas('comprobante', function ($q) {
                        $q->where('firmada', request('firmada'));
                    })->orderBy('id', 'desc')->get();
                else  $results = TransaccionBodega::with('comprobante')->whereIn('motivo_id', $motivos)->whereBetween(
                    'created_at',
                    [
                        date('Y-m-d', strtotime($request->fecha_inicio)),
                        $request->fecha_fin ? date('Y-m-d', strtotime($request->fecha_fin)) : date("Y-m-d h:i:s")
                    ]
                )->whereHas('comprobante', function ($q) {
                    $q->where('firmada', request('firmada'));
                })->orderBy('id', 'desc')->get();
                break;
            case 7: // pedido
                // Log::channel('testing')->info('Log', ['Entró en pedido']);
                $results = TransaccionBodega::with('comprobante')
                    ->whereIn('motivo_id', $motivos)->where('pedido_id', $request->pedido)
                    ->whereBetween(
                        'created_at',
                        [
                            date('Y-m-d', strtotime($request->fecha_inicio)),
                            $request->fecha_fin ? date('Y-m-d', strtotime($request->fecha_fin)) : date("Y-m-d h:i:s")
                        ]
                    )->whereHas('comprobante', function ($q) {
                        $q->where('firmada', request('firmada'));
                    })->orderBy('id', 'desc')->get();
                break;
            case 8: // cliente
                // Log::channel('testing')->info('Log', ['Entró en cliente']);
                $results = TransaccionBodega::with('comprobante')
                    ->whereIn('motivo_id', $motivos)->where('cliente_id', $request->cliente)
                    ->whereBetween(
                        'created_at',
                        [
                            date('Y-m-d', strtotime($request->fecha_inicio)),
                            $request->fecha_fin ? date('Y-m-d', strtotime($request->fecha_fin)) : date("Y-m-d h:i:s")
                        ]
                    )->whereHas('comprobante', function ($q) {
                        $q->where('firmada', request('firmada'));
                    })->orderBy('id', 'desc')->get();
                break;
            case 9: //tarea
                // Log::channel('testing')->info('Log', ['Entró en tarea']);
                $results = TransaccionBodega::with('comprobante')
                    ->whereIn('motivo_id', $motivos)->where('devolucion_id', $request->tarea)
                    ->whereBetween(
                        'created_at',
                        [
                            date('Y-m-d', strtotime($request->fecha_inicio)),
                            $request->fecha_fin ? date('Y-m-d', strtotime($request->fecha_fin)) : date("Y-m-d h:i:s")
                        ]
                    )->whereHas('comprobante', function ($q) {
                        $q->where('firmada', request('firmada'));
                    })->orderBy('id', 'desc')->get();
                break;
            case 10: //transferencia
                // Log::channel('testing')->info('Log', ['Entró en transferencia']);
                $results = TransaccionBodega::with('comprobante')
                    ->whereIn('motivo_id', $motivos)->where('transferencia_id', $request->transferencia)
                    ->whereBetween(
                        'created_at',
                        [
                            date('Y-m-d', strtotime($request->fecha_inicio)),
                            $request->fecha_fin ? date('Y-m-d', strtotime($request->fecha_fin)) : date("Y-m-d h:i:s")
                        ]
                    )->whereHas('comprobante', function ($q) {
                        $q->where('firmada', request('firmada'));
                    })->orderBy('id', 'desc')->get();
                break;
            default:
                // Log::channel('testing')->info('Log', ['Entró en default']);
                $results = TransaccionBodega::with('comprobante')->whereIn('motivo_id', $motivos)->whereHas('comprobante', function ($q) {
                    $q->where('firmada', request('firmada'));
                })->orderBy('id', 'desc')->get(); // todos los egresos
                break;
        }
        return $results;
    }

    /**
     * Devuelve un listado de los materiales usados y su suma total por producto
     */
    public function obtenerSumaMaterialTareaUsado($idSubtarea, $idEmpleado)
    {
        $subtarea = Subtarea::find($idSubtarea);
        $fecha_inicio = Carbon::parse($subtarea->fecha_hora_agendado)->format('Y-m-d');
        $fecha_fin = $subtarea->fecha_hora_finalizacion ? Carbon::parse($subtarea->fecha_hora_finalizacion)->addDay()->format('Y-m-d') : Carbon::now()->addDay()->toDateString();

        return DB::table('seguimientos_materiales_subtareas as sms')
            ->select('dp.descripcion as producto', 'dp.id as detalle_producto_id', DB::raw('SUM(sms.cantidad_utilizada) AS suma_total'))
            ->join('detalles_productos as dp', 'sms.detalle_producto_id', '=', 'dp.id')
            ->whereBetween('sms.created_at', [$fecha_inicio, $fecha_fin])
            ->where('empleado_id', $idEmpleado)
            ->where('subtarea_id', $idSubtarea)
            ->groupBy('detalle_producto_id')
            ->get();
    }

    /**************************************************************************************
     * Devuelve un listado de los materiales de stock usados y su suma total por producto
     **************************************************************************************/
    public function obtenerSumaMaterialStockUsado($idSubtarea, $idEmpleado)
    {
        $subtarea = Subtarea::find($idSubtarea);
        $fecha_inicio = Carbon::parse($subtarea->fecha_hora_agendado)->format('Y-m-d');
        $fecha_fin = $subtarea->fecha_hora_finalizacion ? Carbon::parse($subtarea->fecha_hora_finalizacion)->addDay()->format('Y-m-d') : Carbon::now()->addDay()->toDateString();

        return DB::table('seguimientos_materiales_stock as sms')
            ->select('dp.descripcion as producto', 'dp.id as detalle_producto_id', DB::raw('SUM(sms.cantidad_utilizada) AS suma_total'))
            ->join('detalles_productos as dp', 'sms.detalle_producto_id', '=', 'dp.id')
            ->whereBetween('sms.created_at', [$fecha_inicio, $fecha_fin])
            ->where('empleado_id', $idEmpleado)
            ->where('subtarea_id', $idSubtarea)
            ->groupBy('detalle_producto_id')
            ->get();
    }
}
