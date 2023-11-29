<?php

namespace App\Http\Controllers;

use App\Exports\InventarioExport;
use App\Exports\KardexExport;
use App\Http\Requests\InventarioRequest;
use App\Http\Resources\InventarioResource;
use App\Http\Resources\InventarioResourceExcel;
use App\Http\Resources\VistaInventarioPerchaResource;
use App\Models\ConfiguracionGeneral;
use App\Models\DetalleProductoTransaccion;
use App\Models\EstadoTransaccion;
use App\Models\Inventario;
use App\Models\Motivo;
use App\Models\TipoTransaccion;
use App\Models\TransaccionBodega;
use App\Models\VistaInventarioPercha;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Src\App\InventarioService;
use Src\Shared\Utils;

class InventarioController extends Controller
{
    private $entidad = 'Inventario';
    private $servicio;


    public function __construct()
    {
        $this->servicio = new InventarioService();
        //$this->middleware('can:puede.ver.inventarios')->only('index', 'show');
        $this->middleware('can:puede.crear.inventarios')->only('store');
        $this->middleware('can:puede.editar.inventarios')->only('update');
        $this->middleware('can:puede.eliminar.inventarios')->only('destroy');
    }
    /**
     * Listar
     */
    public function index(Request $request)
    {
        // Log::channel('testing')->info('Log', ['Request recibida', $request->all()]);
        $results = [];
        if ($request->search) {
            $results = $this->servicio->search($request);
        } else {
            $results = $this->servicio->todos($request);
        }
        // if ($search) {
        //     // Log::channel('testing')->info('Log', ['SEARCH', $request->all()]);
        //     $results = Inventario::search($search ?? '')
        //         ->query(function ($query) {
        //             $query->join('detalles_productos', 'inventarios.detalle_id', 'detalles_productos.id')
        //                 ->select(['inventarios.id', 'detalle_id', 'cliente_id', 'condicion_id', 'sucursal_id', 'cantidad', 'estado', 'detalles_productos.descripcion as descripcion']);
        //             // ->orderBy('inventarios.id', 'DESC');
        //         })->get();
        //     $results = InventarioResource::collection($results);
        // }
        // if ($search && $request->cliente_id) {
        //     // Log::channel('testing')->info('Log', ['SEARCH Y CLIENTE', $request->all()]);
        //     $results = Inventario::search($search ?? '')
        //         ->query(function ($query) {
        //             $query->join('detalles_productos', 'inventarios.detalle_id', 'detalles_productos.id')
        //                 ->select(['inventarios.id', 'detalle_id', 'cliente_id', 'condicion_id', 'sucursal_id', 'cantidad', 'estado', 'detalles_productos.descripcion as descripcion']);
        //         })->where('cliente_id', $request['cliente_id'])->get();
        //     $results = InventarioResource::collection($results);
        // }
        // if ($search && $request['sucursal_id']) {
        //     // Log::channel('testing')->info('Log', ['SEARCH Y SUCURSAL', $request->all()]);
        //     $results = Inventario::search($search ?? '')
        //         ->query(function ($query) {
        //             $query->join('detalles_productos', 'inventarios.detalle_id', 'detalles_productos.id')
        //                 ->select(['inventarios.id', 'detalle_id', 'cliente_id', 'condicion_id', 'sucursal_id', 'cantidad', 'estado', 'detalles_productos.descripcion as descripcion']);
        //         })->where('sucursal_id', $request['sucursal_id'])->get();
        //     $results = InventarioResource::collection($results);
        // }
        // if ($search && $request['cliente_id'] && $request['sucursal_id'] && $request->boolean('zeros')) {
        //     //  Log::channel('testing')->info('Log', ['SEARCH Y CLIENTE Y SUCURSAL', $request->all()]);
        //     $results = Inventario::search($search ?? '')
        //         ->query(function ($query) {
        //             $query->join('detalles_productos', 'inventarios.detalle_id', 'detalles_productos.id')
        //                 ->select(['inventarios.id', 'detalle_id', 'cliente_id', 'condicion_id', 'sucursal_id', 'cantidad', 'estado', 'detalles_productos.descripcion as descripcion']);
        //         })->where('cliente_id', $request['cliente_id'])
        //         ->where('sucursal_id', $request['sucursal_id'])->get();
        //     $results = InventarioResource::collection($results);
        // } else {
        //     // Log::channel('testing')->info('Log', ['else linea 69', $request->all()]);
        //     $results = Inventario::search($search ?? '')
        //         ->query(function ($query) {
        //             $query->join('detalles_productos', 'inventarios.detalle_id', 'detalles_productos.id')
        //                 ->select(['inventarios.id', 'detalle_id', 'cliente_id', 'condicion_id', 'sucursal_id', 'cantidad', 'estado', 'detalles_productos.descripcion as descripcion']);
        //         })->where('cliente_id', $request['cliente_id'])
        //         ->where('sucursal_id', $request['sucursal_id'])
        //         ->where('cantidad', '>', 0)->get();
        //     $results = InventarioResource::collection($results);
        // }
        // //si no entra en ningun if
        // if (!$request->hasAny(['search']) && !$request->boolean('zeros')) {
        //     Log::channel('testing')->info('Log', ['if search 92', $request->all()]);
        //     $results = Inventario::ignoreRequest(['zeros'])->filter()->get();
        //     $results = InventarioResource::collection($results);
        // } else {
        //     Log::channel('testing')->info('Log', ['if search 96', $request->all()]);
        //     if ($request->has('zeros')) {
        //         Log::channel('testing')->info('Log', ['if 98', $request->all()]);
        //         $results = Inventario::search($search ?? '')
        //             ->query(function ($query) {
        //                 $query->join('detalles_productos', 'inventarios.detalle_id', 'detalles_productos.id')
        //                     ->select(['inventarios.id', 'detalle_id', 'cliente_id', 'condicion_id', 'sucursal_id', 'cantidad', 'estado', 'detalles_productos.descripcion as descripcion']);
        //             })->ignoreRequest(['zeros', 'search'])->where('cantidad', '>', 0)->OrWhere('por_recibir', '<>', 0)->OrWhere('por_entregar', '<>', 0)->filter()->get();
        //     } else {
        //         Log::channel('testing')->info('Log', ['if 101', $request->all()]);
        //         $results = Inventario::ignoreRequest(['search'])->filter()->get();
        //     }
        //     $results = InventarioResource::collection($results);
        // }
        // if ($sucursal) {
        //     $results = Inventario::where('sucursal_id', $sucursal)->get();
        //     $results = InventarioResource::collection($results);
        // }
        $results = InventarioResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(InventarioRequest $request)
    {
        //Adaptacion de foreign keys
        $datos = $request->validated();
        // $datos['detalle_id']=$request->safe()->only(['detalle'])['detalle'];
        // $datos['sucursal_id']=$request->safe()->only(['sucursal'])['sucursal'];
        $datos['condicion_id'] = $request->safe()->only(['condicion'])['condicion'];
        // $datos['cliente_id']=$request->safe()->only(['cliente'])['cliente'];
        //Respuesta
        // Log::channel('testing')->info('Log', ['listado store de inventario', $request->all()]);
        $item = Inventario::where('detalle_id', $request->detalle_id)
            ->where('sucursal_id', $request->sucursal_id)
            ->where('cliente_id', $request->cliente_id)
            ->where('condicion_id', $request->condicion)
            ->first();
        if ($item) {
            // Log::channel('testing')->info('Log', ['item encontrado', $item]);
            $datos['cantidad'] = $request->cantidad + $item->cantidad;
            $item->update($datos);
            $modelo = $item->refresh();
        } else {
            $modelo = Inventario::create($datos);
        }
        $modelo = new InventarioResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(Inventario $inventario)
    {
        $modelo = new InventarioResource($inventario);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(InventarioRequest $request, Inventario  $inventario)
    {
        //Adaptacion de foreign keys
        $datos = $request->validated();
        // $datos['detalle_id']=$request->safe()->only(['detalle'])['detalle'];
        // $datos['sucursal_id']=$request->safe()->only(['sucursal'])['sucursal'];
        $datos['condicion_id'] = $request->safe()->only(['condicion'])['condicion'];
        // $datos['cliente_id']=$request->safe()->only(['cliente'])['cliente'];
        //Respuesta
        $inventario->update($datos);
        $modelo = new InventarioResource($inventario->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(Inventario $inventario)
    {
        $inventario->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }

    /**
     * Buscar las coincidencias de productos segun el propietario y la sucursal
     */
    public function buscar(Request $request)
    {
        // Log::channel('testing')->info('Log', ['request recibida en buscar de inventario', $request->all()]);
        $results = [];
        $results = Inventario::where('detalle_id', $request->detalle_id)->where('sucursal_id', $request->sucursal_id)->where('cliente_id', $request->cliente_id)->get();
        $results = InventarioResource::collection($results);

        return response()->json(compact('results'));
    }
    /**
     * Buscar las coincidencias de productos segun los id de inventario, el propietario y la sucursal
     */
    public function buscarProductosSegunId(Request $request)
    {
        // Log::channel('testing')->info('Log', ['request recibida en buscarProductos de inventario', $request->all()]);
        $results = [];
        $results = Inventario::whereIn('id', $request->detalles)->where('sucursal_id', $request->sucursal_id)->where('cliente_id', $request->cliente_id)->where('cantidad', '>', 0)->get();
        // Log::channel('testing')->info('Log', ['Resultados obtenidos', $results]);
        $results = InventarioResource::collection($results);

        return response()->json(compact('results'));
    }
    /**
     * Buscar las coincidencias de productos segun el detalle_id, el propietario y la sucursal
     */
    public function buscarProductosSegunDetalleId(Request $request)
    {
        // Log::channel('testing')->info('Log', ['request recibida en buscarProductosSegunDetalleID de inventario', $request->all()]);
        $results = [];
        $results = Inventario::whereIn('detalle_id', $request->detalles)->where('sucursal_id', $request->sucursal_id)->where('cliente_id', $request->cliente_id)->where('cantidad', '>', 0)->get();
        // Log::channel('testing')->info('Log', ['Resultados obtenidos', $results]);
        $results = InventarioResource::collection($results);

        return response()->json(compact('results'));
    }

    public function vista()
    {
        $results = VistaInventarioPercha::consultarItemsInventarioPercha();
        $results = VistaInventarioPerchaResource::collection($results);
        return response()->json(compact('results'));
    }

    /*******************************************
     * REPORTES
     ******************************************/
    /**
     * Imprimir reporte de inventario
     * @param string $id sucursal_id
     */
    public function reporteInventarioPdf($id)
    {
        $configuracion = ConfiguracionGeneral::first();
        $items = [];
        if ($id == 0) {
            $items = Inventario::where('cantidad', '>', 0)
                ->orWhere('por_recibir', '>', 0)->orWhere('por_entregar', '>', 0)->get();
        } else {
            $items = Inventario::where('sucursal_id',  $id)->where('cantidad', '>', 0)
                ->orWhere('por_recibir', '>', 0)->orWhere('por_entregar', '>', 0)->get();
        }
        $resource = InventarioResourceExcel::collection($items);
        Log::channel('testing')->info('Log', ['Elementos sin pasar por el resource', $resource]);
        try {
            $reporte = $resource->resolve();
            // Log::channel('testing')->info('Log', ['Elementos pasados por el resource', $reporte]);
            $pdf = Pdf::loadView('bodega.reportes.inventario_sucursal', compact(['reporte', 'configuracion']));
            $pdf->setPaper('A4', 'landscape');
            $pdf->setOption(['isRemoteEnabled' => true]);
            $pdf->render();
            $file = $pdf->output();

            return $file;
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['ERROR', $ex->getMessage(), $ex->getLine()]);
        }
    }
    /**
     * Imprimir reporte de inventario
     * @param string $id sucursal_id
     */
    public function reporteInventarioExcel($id)
    {
        return Excel::download(new InventarioExport($id), 'reporte.xlsx');
        // return (new InventarioExport($id))->download('reporte.xlsx', Excel::XLSX);
    }

    public function kardex(Request $request)
    {
        $configuracion = ConfiguracionGeneral::first();
        $estadoCompleta = EstadoTransaccion::where('nombre', EstadoTransaccion::COMPLETA)->first();
        $results = [];
        $cont = 0;
        $row = [];
        $tipoTransaccion = TipoTransaccion::where('nombre', 'INGRESO')->first();
        $ids_motivos_ingresos = Motivo::where('tipo_transaccion_id', $tipoTransaccion->id)->get('id');
        $ids_itemsInventario = Inventario::where('detalle_id', $request->detalle)->orderBy('updated_at', 'desc')->get('id');
        if ($request->fecha_inicio && $request->fecha_fin) {
            // Log::channel('testing')->info('Log', ['Request', $request->all(), 'primer if']);
            $movimientos = DetalleProductoTransaccion::whereIn('inventario_id', $ids_itemsInventario)
                ->whereBetween('detalle_producto_transaccion.created_at', [date('Y-m-d', strtotime($request->fecha_inicio)), date('Y-m-d', strtotime($request->fecha_fin))])
                ->join('transacciones_bodega', 'transacciones_bodega.id', '=', 'detalle_producto_transaccion.transaccion_id')
                ->where('transacciones_bodega.estado_id', $estadoCompleta->id)->orderBy('detalle_producto_transaccion.created_at', 'asc')->get();
        }
        if ($request->fecha_inicio && !$request->fecha_fin) {
            // Log::channel('testing')->info('Log', ['Request', $request->all(), 'segundo if', date('Y-m-d h:i:s')]);
            $movimientos = DetalleProductoTransaccion::whereIn('inventario_id', $ids_itemsInventario)
                ->whereBetween('detalle_producto_transaccion.created_at', [date('Y-m-d', strtotime($request->fecha_inicio)), date("Y-m-d h:i:s")])
                ->join('transacciones_bodega', 'transacciones_bodega.id', '=', 'detalle_producto_transaccion.transaccion_id')
                ->where('transacciones_bodega.estado_id', $estadoCompleta->id)->orderBy('detalle_producto_transaccion.created_at', 'asc')->get();
        }
        if (!$request->fecha_inicio && !$request->fecha_fin) {
            // Log::channel('testing')->info('Log', ['Request', $request->all(), 'tercer if']);
            $movimientos = DetalleProductoTransaccion::whereIn('inventario_id', $ids_itemsInventario)
                ->join('transacciones_bodega', 'transacciones_bodega.id', '=', 'detalle_producto_transaccion.transaccion_id')
                ->where('transacciones_bodega.estado_id', $estadoCompleta->id)->orderBy('detalle_producto_transaccion.created_at', 'asc')->get();
        }
        // Log::channel('testing')->info('Log', ['Listado de movimientos', $movimientos]);
        foreach ($movimientos as $movimiento) {
            // Log::channel('testing')->info('Log', ['Movimiento', $movimiento]);
            $row['id'] = $movimiento->inventario->detalle->id;
            $row['detalle'] = $movimiento->inventario->detalle->descripcion;
            $row['num_transaccion'] = $movimiento->transaccion->id;
            $row['motivo'] = $movimiento->transaccion->motivo->nombre;
            $row['tipo'] = $movimiento->transaccion->motivo->tipoTransaccion->nombre;
            $row['cantidad'] = $movimiento->cantidad_inicial;
            $row['cant_anterior'] = $cont == 0 ? 0 : $row['cant_actual'];
            $row['cant_actual'] = $cont == 0 ? $movimiento->cantidad_inicial : ($row['tipo'] == 'INGRESO' ? $row['cant_actual'] + $movimiento->cantidad_inicial : $row['cant_actual'] - $movimiento->cantidad_inicial);
            $row['fecha'] = date('d/m/Y', strtotime($movimiento->created_at));
            $results[$cont] = $row;
            $cont++;
        }
        rsort($results); //aqui se ordena el array en forma descendente
        switch ($request->tipo_rpt) {
            case 'excel':
                try {
                    return Excel::download(new KardexExport(collect($results)), 'kardex.xlsx');
                } catch (Exception $ex) {
                    Log::channel('testing')->info('Log', ['ERROR', $ex->getMessage(), $ex->getLine()]);
                }
                break;
            case 'pdf':
                try {
                    $pdf = Pdf::loadView('bodega.reportes.kardex', compact('results', 'configuracion'));
                    $pdf->setPaper('A4', 'landscape');
                    $pdf->render();
                    $file = $pdf->output();
                    return $file;
                } catch (Exception $ex) {
                    Log::channel('testing')->info('Log', ['ERROR', $ex->getMessage(), $ex->getLine()]);
                }
                break;
            default:
                return response()->json(compact('results'));
        }
    }
}
