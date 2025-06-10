<?php

namespace Src\App;

use App\Helpers\Filtros\FiltroSearchHelper;
use App\Models\Autorizacion;
use App\Models\Comprobante;
use App\Models\DetallePedidoProducto;
use App\Models\DetalleProducto;
use App\Models\DetalleProductoTransaccion;
use App\Models\EstadoTransaccion;
use App\Models\Inventario;
use App\Models\Motivo;
use App\Models\Producto;
use App\Models\Subtarea;
use App\Models\TipoTransaccion;
use App\Models\TransaccionBodega;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\App\Bodega\PedidoService;
use Src\Config\Autorizaciones;
use Src\Config\ClientesCorporativos;
use Src\Config\Constantes;
use Src\Config\EstadosTransacciones;
use Throwable;

class TransaccionBodegaEgresoService
{
    public static \Illuminate\Support\Collection|array|Collection $motivos;

    public function __construct()
    {
        $tipo_transaccion = TipoTransaccion::where('nombre', TipoTransaccion::EGRESO)->first();
        self::$motivos = Motivo::where('tipo_transaccion_id', $tipo_transaccion->id)->get('id');
    }


    /**
     * @throws Exception
     */
    public static function obtenerFiltrosIndice(?string $estado)
    {
        if (is_null($estado))
            return '';
        else
            $filtros = match (strtoupper($estado)) {
                EstadoTransaccion::PENDIENTE => [
                    ['clave' => 'firmada', 'valor' => 0],
                    ['clave' => 'estado_comprobante', 'valor' => EstadoTransaccion::PENDIENTE, 'operador' => 'AND'],
                    ['clave' => 'tipo_transaccion', 'valor' => TipoTransaccion::EGRESO, 'operador' => 'AND'],
                    ['clave' => 'autorizacion', 'valor' => Autorizacion::APROBADO, 'operador' => 'AND'],
                ],
                EstadoTransaccion::PARCIAL => [
                    ['clave' => 'estado_comprobante', 'valor' => EstadoTransaccion::PARCIAL],
                    ['clave' => 'tipo_transaccion', 'valor' => TipoTransaccion::EGRESO, 'operador' => 'AND'],
                ],
                EstadoTransaccion::COMPLETA => [
                    ['clave' => 'firmada', 'valor' => 1],
                    ['clave' => 'estado_comprobante', 'valor' => TransaccionBodega::ACEPTADA, 'operador' => 'AND'],
                    ['clave' => 'comprobante', 'valor' => 'na', 'operador' => 'OR'],
                    ['clave' => 'tipo_transaccion', 'valor' => TipoTransaccion::EGRESO, 'operador' => 'AND'],
                ],
                'ANULADA' => [
                    ['clave' => 'estado', 'valor' => '"' . EstadoTransaccion::ANULADA . '"'],
                    ['clave' => 'tipo_transaccion', 'valor' => TipoTransaccion::EGRESO, 'operador' => 'AND'],
                ],
                default => throw new Exception("El estado '$estado' no es válido para filtros."),
            };

        return FiltroSearchHelper::formatearFiltrosPorMotor($filtros);
    }

    /**
     * @throws Exception
     */
    public static function listar($request, $paginate = false)
    {
        $estado = $request->estado;
        $search = $request->search;
        $filtrosAlgolia = self::obtenerFiltrosIndice($estado);
        $results = [];
        if (auth()->user()->hasRole([User::ROL_BODEGA, User::ROL_ADMINISTRADOR, User::ROL_CONTABILIDAD, User::ROL_CONSULTA])) { //si es bodeguero
            if ($estado) {
                switch ($estado) {
                    case EstadoTransaccion::PENDIENTE:
                        $query = TransaccionBodega::whereIn('motivo_id', self::$motivos)
                            ->whereHas('comprobante', function ($q) {
                                $q->where('firmada', false)->where('estado', EstadoTransaccion::PENDIENTE);
                            })
                            ->with('comprobante')
                            ->orderBy('id', 'desc');
                        break;
                    case EstadoTransaccion::PARCIAL:
                        $query = TransaccionBodega::with('comprobante')
                            ->whereIn('motivo_id', self::$motivos)
                            ->whereHas('comprobante', function ($q) {
                                $q->where('estado', EstadoTransaccion::PARCIAL);
                            })
                            ->orderBy('id', 'desc');
                        break;
                    case EstadoTransaccion::COMPLETA:
                        $query = TransaccionBodega::with('comprobante')->whereIn('motivo_id', self::$motivos)
                            ->where(function ($query) {
                                $query->whereHas('comprobante', function ($q) {
                                    $q->where('firmada', true)->where('estado', TransaccionBodega::ACEPTADA);
                                })->orWhereDoesntHave('comprobante');
                            })->where('autorizacion_id', Autorizaciones::APROBADO)->orderBy('id', 'desc');

                        break;
                    case 'ANULADA':
                        $query = TransaccionBodega::whereIn('motivo_id', self::$motivos)
                            ->where('estado_id', EstadosTransacciones::ANULADA)
                            ->orderBy('id', 'desc');
                        break;
                    default:
                        throw new Exception("El estado '$estado' no es válido.");
                }
                return buscarConAlgoliaFiltrado(TransaccionBodega::class, $query, 'id', $search, Constantes::PAGINATION_ITEMS_PER_PAGE, request('page'), !!$paginate, $filtrosAlgolia);
            } else {
                $results = TransaccionBodega::whereIn('motivo_id', self::$motivos)
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
                        $query = TransaccionBodega::with('comprobante')->whereIn('motivo_id', self::$motivos)->whereHas('comprobante', function ($q) {
                            $q->where('firmada', false);
                        })->where('cliente_id', ClientesCorporativos::TELCONET)->orderBy('id', 'desc');

                        break;
                    case EstadoTransaccion::PARCIAL:
                        $query = TransaccionBodega::with('comprobante')->whereIn('motivo_id', self::$motivos)->whereHas('comprobante', function ($q) {
                            $q->where('estado', EstadoTransaccion::PARCIAL);
                        })->where('cliente_id', ClientesCorporativos::TELCONET)->orderBy('id', 'desc');
                        break;
                    case EstadoTransaccion::COMPLETA:
                        $query = TransaccionBodega::with('comprobante')->whereIn('motivo_id', self::$motivos)
                            ->where(function ($query) {
                                $query->whereHas('comprobante', function ($q) {
                                    $q->where('firmada', true)->where('estado', TransaccionBodega::ACEPTADA);
                                })->orWhereDoesntHave('comprobante');
                            })->where('autorizacion_id', Autorizaciones::APROBADO)->where('cliente_id', ClientesCorporativos::TELCONET)->orderBy('id', 'desc');
                        break;
                    case 'ANULADA':
                        $query = TransaccionBodega::whereIn('motivo_id', self::$motivos)->where('estado_id', EstadosTransacciones::ANULADA)->where('cliente_id', ClientesCorporativos::TELCONET)->orderBy('id', 'desc');
                        break;
                    default:
                        throw new Exception("El estado '$estado' no es válido.");
                }
                return buscarConAlgoliaFiltrado(TransaccionBodega::class, $query, 'id', $search, Constantes::PAGINATION_ITEMS_PER_PAGE, request('page'), !!$paginate, $filtrosAlgolia);
            } else
                $results = TransaccionBodega::whereIn('motivo_id', self::$motivos)
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
        return TransaccionBodega::select(["transacciones_bodega.id", "justificacion", "comprobante", "fecha_limite", "solicitante_id", "motivo_id", "tarea_id", "sucursal_id", "per_autoriza_id", "per_atiende_id", "per_retira_id",])
            ->where('tarea_id', '=', $tarea_id)
            ->join('tiempo_estado_transaccion', function ($join) {
                $join->on('transacciones_bodega.id', '=', 'tiempo_estado_transaccion.transaccion_id')
                    ->where('tiempo_estado_transaccion.updated_at', DB::raw('(select max(updated_at ) from tiempo_estado_transaccion where transaccion_id = transacciones_bodega.id)'))
                    ->where('tiempo_estado_transaccion.estado_id', DB::raw('(select id from estados_transacciones_bodega where estados_transacciones_bodega.nombre ="COMPLETA")'));
            })
            ->join('estados_transacciones_bodega', 'tiempo_estado_transaccion.estado_id', '=', 'estados_transacciones_bodega.id')
            ->where('estados_transacciones_bodega.nombre', EstadoTransaccion::COMPLETA)
            ->get();
    }


    public function obtenerListadoMaterialesPorTarea($tarea_id)
    {
        // Log::channel('testing')->info('Log', ['resultados de obtener listado materiales por tarea', $results]);
        $results = DB::table('detalle_producto_transaccion')
            ->select(DB::raw('sum(cantidad_inicial) as cantidad'), 'detalle_id')
            ->join('transacciones_bodega', 'detalle_producto_transaccion.transaccion_id', '=', 'transacciones_bodega.id')
            ->where('transacciones_bodega.tarea_id', '=', $tarea_id)
            ->groupBy('detalle_id')
            ->get();

        return $results->map(fn($items) => [
            'cantidad_despachada' => intval($items->cantidad),
            'detalle' => DetalleProducto::find($items->detalle_id)->descripcion,
        ]);
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
        return collect($results)->map(fn($items) => [
            'cantidad_despachada' => intval($items->cantidad),
            'detalle' => DetalleProducto::find($items->detalle_id)->descripcion,
        ]);
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

        return collect($results)->map(fn($items) => [
            'cantidad_despachada' => intval($items->cantidad),
            'detalle' => DetalleProducto::find($items->detalle_id)->descripcion,
        ]);
    }

    public static function filtrarEgresosResponsablePorCategoria(Request $request)
    {
        $ids_productos = Producto::whereIn('categoria_id', $request->categorias)->pluck('id');
        $ids_detalles = DetalleProducto::whereIn('producto_id', $ids_productos)->pluck('id');
        $ids_inventarios = Inventario::whereIn('detalle_id', $ids_detalles)->pluck('id');
        $ids_transacciones = DetalleProductoTransaccion::whereIn('inventario_id', $ids_inventarios)->pluck('transaccion_id');
//        Log::channel('testing')->info('Log', ['Request', $ids_transacciones]);
        return TransaccionBodega::with('comprobante')
            ->whereIn('motivo_id', self::$motivos)
            ->whereIn('id', $ids_transacciones)
            ->where('responsable_id', $request->responsable)
            ->whereBetween(
                'created_at',
                [
                    date('Y-m-d', strtotime($request->fecha_inicio)),
                    $request->fecha_fin ? date('Y-m-d', strtotime($request->fecha_fin)) : date("Y-m-d h:i:s")
                ]
            )
            ->whereHas('comprobante', function ($q) {
                $q->where('firmada', true);
            })->orderBy('id')->get();
    }

    public static function filtrarEgresoPorTipoFiltro($request)
    {
        // Log::channel('testing')->info('Log', ['Request', $request->all()]);
        $tipo_transaccion = TipoTransaccion::where('nombre', TipoTransaccion::EGRESO)->first();
//        $motivos = Motivo::where('tipo_transaccion_id', $tipo_transaccion->id)->get('id');
        switch ($request->tipo) {
            case 0: //persona que solicita el ingreso
                // Log::channel('testing')->info('Log', ['Entró en solicitante']);
                $results = TransaccionBodega::with('comprobante')->whereIn('motivo_id', self::$motivos)->where('solicitante_id', $request->solicitante)
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
                    ->whereIn('motivo_id', self::$motivos)->where('per_autoriza_id', $request->per_autoriza)
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
                    ->whereIn('motivo_id', self::$motivos)->where('per_retira_id', $request->per_retira)
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
                Log::channel('testing')->info('Log', ['Entró en persona responsable', $request]);
                $results = TransaccionBodega::with('comprobante')
                    ->whereIn('motivo_id', self::$motivos)->where('responsable_id', $request->responsable)
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
                    ->whereIn('motivo_id', self::$motivos)->where('per_atiende_id', $request->per_atiende)
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
                if ($request->sucursal != 0) $results = TransaccionBodega::with('comprobante')
                    ->whereIn('motivo_id', self::$motivos)->where('sucursal_id', $request->sucursal)
                    ->whereBetween(
                        'created_at',
                        [
                            date('Y-m-d', strtotime($request->fecha_inicio)),
                            $request->fecha_fin ? date('Y-m-d', strtotime($request->fecha_fin)) : date("Y-m-d h:i:s")
                        ]
                    )->whereHas('comprobante', function ($q) {
                        $q->where('firmada', request('firmada'));
                    })->orderBy('id', 'desc')->get();
                else  $results = TransaccionBodega::with('comprobante')->whereIn('motivo_id', self::$motivos)->whereBetween(
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
                    ->whereIn('motivo_id', self::$motivos)->where('pedido_id', $request->pedido)
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
                    ->whereIn('motivo_id', self::$motivos)->where('cliente_id', $request->cliente)
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
                    ->whereIn('motivo_id', self::$motivos)->where('devolucion_id', $request->tarea)
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
                    ->whereIn('motivo_id', self::$motivos)->where('transferencia_id', $request->transferencia)
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
            case 11: // categorias de materiales
                // Log::channel('testing')->info('Log', ['Entró en categorias', $request->all()]);
                $ids_productos = Producto::whereIn('categoria_id', $request->categorias)->pluck('id');
                $ids_detalles = DetalleProducto::whereIn('producto_id', $ids_productos)->pluck('id');
                $ids_inventarios = Inventario::whereIn('detalle_id', $ids_detalles)->pluck('id');
                $ids_transacciones = DetalleProductoTransaccion::whereIn('inventario_id', $ids_inventarios)->pluck('transaccion_id');
                $results = TransaccionBodega::with('comprobante')
                    ->whereIn('motivo_id', self::$motivos)
                    ->whereIn('id', $ids_transacciones)
                    ->whereBetween(
                        'created_at',
                        [
                            date('Y-m-d', strtotime($request->fecha_inicio)),
                            $request->fecha_fin ? date('Y-m-d', strtotime($request->fecha_fin)) : date("Y-m-d h:i:s")
                        ]
                    )
                    ->whereHas('comprobante', function ($q) {
                        $q->where('firmada', request('firmada'));
                    })->orderBy('id', 'desc')->get();
                break;
            default:
                // Log::channel('testing')->info('Log', ['Entró en default']);
                $results = TransaccionBodega::with('comprobante')->whereIn('motivo_id', self::$motivos)->whereHas('comprobante', function ($q) {
                    $q->where('firmada', request('firmada'));
                })->orderBy('id', 'desc')->get(); // todos los egresos
                break;
        }
        return $results;
    }

    /*******************************************************************************************
     * Devuelve un listado de los materiales usados y su suma total por producto - Seguimiento
     *******************************************************************************************/
    public function obtenerSumaMaterialTareaUsado($idSubtarea, $idEmpleado)
    {
        $subtarea = Subtarea::find($idSubtarea);
        $fecha_inicio = Carbon::parse($subtarea->fecha_hora_agendado)->format('Y-m-d');
        $fecha_fin = $subtarea->fecha_hora_finalizacion ? Carbon::parse($subtarea->fecha_hora_finalizacion)->addDay()->format('Y-m-d') : Carbon::now()->addDay()->toDateString();

        return DB::table('seguimientos_materiales_subtareas as sms')
            ->select('dp.descripcion as producto', 'dp.id as detalle_producto_id', DB::raw('SUM(sms.cantidad_utilizada) AS suma_total'), 'sms.cliente_id')
            ->join('detalles_productos as dp', 'sms.detalle_producto_id', '=', 'dp.id')
            ->whereBetween('sms.created_at', [$fecha_inicio, $fecha_fin])
            ->where('empleado_id', $idEmpleado)
            ->where('subtarea_id', $idSubtarea)
            ->groupBy('detalle_producto_id', 'cliente_id')
            ->get();
        // ->groupBy('detalle_producto_id')
    }

    /***************************************************************************************************
     * Devuelve un listado de los materiales de stock usados y su suma total por producto - Seguimiento
     ***************************************************************************************************/
    public function obtenerSumaMaterialStockUsado($idSubtarea, $idEmpleado, $idCliente)
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
            ->where('cliente_id', $idCliente)
            ->groupBy('detalle_producto_id')
            ->get();
    }

    /*************************************************************************************
     * STOCK - Aqui se listan los productos utilizados de varios clientes, no solo de uno
     *************************************************************************************/
    public function obtenerSumaMaterialStockUsadoHistorial($idSubtarea, $idEmpleado)
    {
        $subtarea = Subtarea::find($idSubtarea);
        $fecha_inicio = Carbon::parse($subtarea->fecha_hora_agendado)->format('Y-m-d');
        $fecha_fin = $subtarea->fecha_hora_finalizacion ? Carbon::parse($subtarea->fecha_hora_finalizacion)->addDay()->format('Y-m-d') : Carbon::now()->addDay()->toDateString();

        return DB::table('seguimientos_materiales_stock as sms')
            ->select('dp.descripcion as producto', 'dp.id as detalle_producto_id', DB::raw('SUM(sms.cantidad_utilizada) AS suma_total'), 'sms.cliente_id')
            ->join('detalles_productos as dp', 'sms.detalle_producto_id', '=', 'dp.id')
            ->whereBetween('sms.created_at', [$fecha_inicio, $fecha_fin])
            ->where('empleado_id', $idEmpleado)
            ->where('subtarea_id', $idSubtarea)
            ->groupBy('detalle_producto_id', 'cliente_id')
            ->get();
    }

    /*************************************************************************************
     * TAREA - Aqui se listan los productos utilizados de varios clientes, no solo de uno
     *************************************************************************************/
    public function obtenerSumaMaterialTareaUsadoHistorial($idSubtarea, $idEmpleado)
    {
        $subtarea = Subtarea::find($idSubtarea);
        $fecha_inicio = Carbon::parse($subtarea->fecha_hora_agendado)->format('Y-m-d');
        $fecha_fin = $subtarea->fecha_hora_finalizacion ? Carbon::parse($subtarea->fecha_hora_finalizacion)->addDay()->format('Y-m-d') : Carbon::now()->addDay()->toDateString();

        return DB::table('seguimientos_materiales_subtareas as sms')
            ->select('dp.descripcion as producto', 'dp.id as detalle_producto_id', DB::raw('SUM(sms.cantidad_utilizada) AS suma_total'), 'sms.cliente_id')
            ->join('detalles_productos as dp', 'sms.detalle_producto_id', '=', 'dp.id')
            ->whereBetween('sms.created_at', [$fecha_inicio, $fecha_fin])
            ->where('empleado_id', $idEmpleado)
            ->where('subtarea_id', $idSubtarea)
            ->groupBy('detalle_producto_id', 'cliente_id')
            ->get();
    }


    /**
     * @throws Throwable
     */
    public function modificarItemEgresoPendiente(Request $request, TransaccionBodega $transaccion)
    {
        $item_inventario = Inventario::find($request->item['id']);
        $detalle_producto_transaccion = DetalleProductoTransaccion::where('transaccion_id', $transaccion->id)->where('inventario_id', $request->item['id'])->first();
        try {
            DB::beginTransaction();
            if (is_null($request->item['cantidad']) || $request->item['cantidad'] < 0) throw new Exception('La cantidad debe ser un numero mayor o igual a 0');
            //primero verificamos si se va a restar o no
            if ($request->item['cantidad'] > $request->item['pendiente']) {
                // Esto significa que se va a despachar más cantidad
                // En este caso se va a restar cantidad del inventario

                // 200 - 100 = 100 > 870 = false
                if (($request->item['cantidad'] - $request->item['pendiente']) > $item_inventario->cantidad)
                    throw new Exception('La cantidad para el item ' . $request->item['descripcion'] . ' no debe ser superior a la existente en el inventario. En inventario: ' . $item_inventario->cantidad);

                $item_inventario->cantidad -= ($request->item['cantidad'] - $request->item['pendiente']);
            } else {
                // Esto significa que se va a despachar menos cantidad o a quitar el item
                // En este caso se va a sumar cantidad al inventario
                if ($request->item['cantidad'] === 0) { //Significa que se va a eliminar el item del despacho, la cantidad inicial regresa al inventario
                    $item_inventario->cantidad += $detalle_producto_transaccion->cantidad_inicial;
                    $item_inventario->save();
                    $detalle_producto_transaccion->delete();
                    // Aqui se verifica si tiene pedido y se borra la cantidad de despachado
                    if ($transaccion->pedido_id) PedidoService::modificarCantidadDespachadoEnDetallePedidoProducto($item_inventario->detalle_id, $transaccion->pedido_id, $request->item['cantidad']);
                    DB::commit();
                    return;
                }
                // cuando cantidad > 0 se suma al inventario la diferencia entre pendiente y cantidad
                $item_inventario->cantidad += ($request->item['pendiente'] - $request->item['cantidad']);
            }
            $item_inventario->save();
            $detalle_producto_transaccion->cantidad_inicial = $request->item['cantidad'];
            $detalle_producto_transaccion->save();
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * @throws Throwable
     */
    public function modificarItemEgresoParcial(Request $request, TransaccionBodega $transaccion)
    {
        $item_inventario = Inventario::find($request->item['id']);
        $detalle_producto_transaccion = DetalleProductoTransaccion::where('transaccion_id', $transaccion->id)->where('inventario_id', $request->item['id'])->first();
        if ($detalle_producto_transaccion->recibido > 0 && $request->item['cantidad'] > $detalle_producto_transaccion->cantidad_inicial) throw new Exception('No se puede despachar más cantidad a un ítem que ya tiene una cantidad recibida mayor a 0');
        if ($request->item['cantidad'] === 0 && $detalle_producto_transaccion->recibido > 0) throw new Exception('No puede establecer cantidad cero para un item que ya tiene un valor de recibido');
        try {
            DB::beginTransaction();
            //primero verificamos si se va a restar o no
            /**
             * Que pasa si:
             * cantidad = 10
             * pendiente = 6
             * recibido = 4
             */
            // item['cantidad'] = 8

            if ($request->item['cantidad'] > $request->item['pendiente']) {
                // Esto significa que se va a despachar más cantidad
                // En este caso se va a restar cantidad del inventario
                // 10 - 1 = 9 > 2 = true
                // 2 - 1 = 1 > 2 = false
                if (($request->item['cantidad'] - $request->item['pendiente']) > $item_inventario->cantidad)
                    throw new Exception('La cantidad para el item ' . $request->item['descripcion'] . ' no debe ser superior a la existente en el inventario. En inventario: ' . $item_inventario->cantidad);

                $item_inventario->cantidad -= ($request->item['cantidad'] - $request->item['pendiente']);
            } else {
                // Esto significa que se va a despachar menos cantidad o a quitar el item
                // En este caso se va a sumar cantidad al inventario
                if ($request->item['cantidad'] === 0 && $detalle_producto_transaccion->recibido === 0) { //Significa que se va a eliminar el item del despacho, la cantidad inicial regresa al inventario
                    $item_inventario->cantidad += $detalle_producto_transaccion->cantidad_inicial;
                    $item_inventario->save();
                    $detalle_producto_transaccion->delete();
                    $this->actualizarTransaccionEgreso($request->transaccion_id);
                    if (!is_null($detalle_producto_transaccion->transaccion->pedido_id)) {
                        // Se actualiza la cantidad en el pedido
                        $item_pedido = DetallePedidoProducto::where('pedido_id', $detalle_producto_transaccion->transaccion->pedido_id)->where('detalle_id', $item_inventario->detalle_id)->first();
                        $item_pedido->despachado -= $detalle_producto_transaccion->cantidad_inicial;
                        $item_pedido->save();
                    }
                    DB::commit();
                    return;
                }
                //Ejemplo de cantidad_inicial=10 y recibido=5
                // En su momento se restó 10 al inventario
                // La transaccion tiene recibido 5, entonces en el frontend se muesta: cantidad=10, pendiente=5 y recibido=5
                if ($request->item['cantidad'] === $detalle_producto_transaccion->recibido) {
                    $item_inventario->cantidad += ($detalle_producto_transaccion->cantidad_inicial - $detalle_producto_transaccion->recibido);
                    $item_inventario->save();
                    $detalle_producto_transaccion->cantidad_inicial = $detalle_producto_transaccion->recibido;
                    $detalle_producto_transaccion->save();
                    $this->actualizarTransaccionEgreso($request->transaccion_id);
                    DB::commit();
                    return;
                }
                // Otro ejemplo de cantidad_inicial=10 y recibido=3, nos mostraria pendiente=7
                // Desde el front viene
                // cantidad = 6
                // pendiente= 7
                // recibido = 3
                // cuando cantidad > 0 se suma al inventario la diferencia entre pendiente y cantidad
                $item_inventario->cantidad += ($detalle_producto_transaccion->cantidad_inicial - $request->item['cantidad']);
            }
            $item_inventario->save();
            $detalle_producto_transaccion->cantidad_inicial = $request->item['cantidad'];
            $detalle_producto_transaccion->save();
            $this->actualizarTransaccionEgreso($request->transaccion_id);

            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    private function actualizarTransaccionEgreso(int $transaccion_id)
    {
        //Verificación de la transacción para saber si se actualizó correctamente
        $transaccion = TransaccionBodega::find($transaccion_id);
        if (Comprobante::verificarEgresoCompletado($transaccion->id)) {
            $transaccion->estado_id = EstadosTransacciones::COMPLETA;
            $transaccion->save();
            $comprobante = Comprobante::where('transaccion_id', $transaccion->id)->first();
            $comprobante->estado = TransaccionBodega::ACEPTADA;
            $comprobante->observacion = $transaccion->observacion_est;
            $comprobante->firmada = !$comprobante->firmada;
            $comprobante->save();
        }
    }
}
