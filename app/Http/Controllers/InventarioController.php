<?php

namespace App\Http\Controllers;

use App\Exports\InventarioExport;
use App\Exports\KardexExport;
use App\Http\Requests\InventarioRequest;
use App\Http\Resources\InventarioResource;
use App\Http\Resources\InventarioResourceExcel;
use App\Http\Resources\ItemDetallePreingresoMaterialResource;
use App\Http\Resources\VistaInventarioPerchaResource;
use App\Models\ConfiguracionGeneral;
use App\Models\DetalleProductoTransaccion;
use App\Models\EstadoTransaccion;
use App\Models\Inventario;
use App\Models\ItemDetallePreingresoMaterial;
use App\Models\Motivo;
use App\Models\TipoTransaccion;
use App\Models\TransaccionBodega;
use App\Models\VistaInventarioPercha;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use OwenIt\Auditing\Models\Audit;
use Src\App\InventarioService;
use Src\Config\EstadosTransacciones;
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
        $results = InventarioResource::collection($results);
        return response()->json(compact('results'));
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
        // Log::channel('testing')->info('Log', ['Elementos sin pasar por el resource', $resource]);
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
            throw ValidationException::withMessages([
                'pdf' => [$ex->getMessage()],
            ]);
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
        // Log::channel('testing')->info('Log', ['Request kardex', $request->all()]);
        $configuracion = ConfiguracionGeneral::first();
        // $estadoCompleta = EstadoTransaccion::where('nombre', EstadoTransaccion::COMPLETA)->first();
        $results = [];
        $results2 = [];
        $cont = 0;
        $cantAudit = 0;
        $row = [];
        $tipoTransaccion = TipoTransaccion::where('nombre', 'INGRESO')->first();
        $ids_motivos_ingresos = Motivo::where('tipo_transaccion_id', $tipoTransaccion->id)->get('id');
        $ids_itemsInventario = Inventario::where('detalle_id', $request->detalle_id)
            ->when($request->sucursal_id, function ($q) use ($request) {
                $q->where('sucursal_id', $request->sucursal_id);
            })->orderBy('updated_at', 'desc')->get('id');
        if ($request->fecha_inicio && $request->fecha_fin) {
            $movimientos = DetalleProductoTransaccion::whereIn('inventario_id', $ids_itemsInventario)
                ->whereBetween('detalle_producto_transaccion.created_at', [date('Y-m-d', strtotime($request->fecha_inicio)), date('Y-m-d', strtotime($request->fecha_fin))])
                ->orderBy('detalle_producto_transaccion.created_at', 'asc')->get();
        }
        if ($request->fecha_inicio && !$request->fecha_fin) {
            $movimientos = DetalleProductoTransaccion::whereIn('inventario_id', $ids_itemsInventario)
                ->whereBetween('detalle_producto_transaccion.created_at', [date('Y-m-d', strtotime($request->fecha_inicio)), date("Y-m-d h:i:s")])
                ->orderBy('detalle_producto_transaccion.created_at', 'asc')->get();
        }
        if (!$request->fecha_inicio && !$request->fecha_fin) {
            $movimientos = DetalleProductoTransaccion::whereIn('inventario_id', $ids_itemsInventario)
                ->orderBy('detalle_producto_transaccion.created_at', 'asc')->get();
        }
        //Obtener los ingresos y egresos anulados
        $movimientosIngresosAnulados = DetalleProductoTransaccion::withWhereHas('transaccion', function ($query) use ($ids_motivos_ingresos, $movimientos) {
            $query->whereIn('motivo_id', $ids_motivos_ingresos)
                ->whereIn('id', $movimientos->pluck('transaccion_id'))
                ->where('estado_id', EstadosTransacciones::ANULADA);
        })
            ->whereIn('inventario_id', $ids_itemsInventario)
            ->orderBy('detalle_producto_transaccion.created_at', 'asc')->get();
        $movimientosEgresosAnulados = DetalleProductoTransaccion::withWhereHas('transaccion', function ($query) use ($ids_motivos_ingresos, $movimientos) {
            $query->where('estado_id', EstadosTransacciones::ANULADA)
                ->whereIn('id', $movimientos->pluck('transaccion_id'))
                ->whereNotIn('motivo_id', $ids_motivos_ingresos);
        })
            ->whereIn('inventario_id', $ids_itemsInventario)
            ->orderBy('detalle_producto_transaccion.created_at', 'asc')->get();

        foreach ($movimientos as $movimiento) {
            Log::channel('testing')->info('Log', [$cont, 'Movimiento', $movimiento]);
            // Log::channel('testing')->info('Log', [$cont, 'Movimiento', $movimiento]);
            if ($cont == 0) {
                $audit = Audit::where('auditable_id', $movimiento->inventario_id)
                    ->where('auditable_type', Inventario::class)
                    ->whereBetween('updated_at', [
                        Carbon::parse($movimiento->created_at)->subSecond(2),
                        Carbon::parse($movimiento->created_at)->addSecond(2),
                    ])
                    ->first();
                // Log::channel('testing')->info('Log', ['audit', $audit]);
                if ($audit) $cantAudit = count($audit->old_values)>0? $audit->old_values['cantidad']:0;
            }
            $row['id'] = $movimiento->inventario->detalle->id;
            $row['id'] = $cont + 1;
            $row['detalle'] = $movimiento->inventario->detalle->descripcion;
            $row['num_transaccion'] = $movimiento->transaccion->id;
            $row['motivo'] = $movimiento->transaccion->motivo->nombre;
            $row['tipo'] = $movimiento->transaccion->motivo->tipoTransaccion->nombre;
            $row['sucursal'] = $movimiento->inventario->sucursal?->lugar;
            $row['cantidad'] = $movimiento->cantidad_inicial;
            $row['cant_anterior'] = $cont == 0 ? $cantAudit : $row['cant_actual'];
            $row['cant_actual'] = ($row['tipo'] == 'INGRESO' ? $row['cant_anterior'] + $movimiento->cantidad_inicial : $row['cant_anterior'] - $movimiento->cantidad_inicial);
            // $row['cant_actual'] = $cont == 0 ? $movimiento->cantidad_inicial : ($row['tipo'] == 'INGRESO' ? $row['cant_actual'] + $movimiento->cantidad_inicial : $row['cant_actual'] - $movimiento->cantidad_inicial);
            $row['fecha'] = date('d/m/Y', strtotime($movimiento->created_at));
            $results[$cont] = $row;
            $cont++;
            //Aqui se verifica si contiene el id actual la collection de ingresos anulados
            $ingresoCoincidente = $movimientosIngresosAnulados->firstWhere('id', $movimiento->id);
            if ($ingresoCoincidente !== null) {
                //Se ingresa el movimiento como anulado y se resta al inventario
                $row['id'] = $cont + 1;
                $row['detalle'] = $ingresoCoincidente->inventario->detalle->descripcion;
                $row['num_transaccion'] = $ingresoCoincidente->transaccion->id;
                $row['motivo'] = $ingresoCoincidente->transaccion->motivo->nombre;
                $row['tipo'] = 'ANULACION';
                $row['sucursal'] = $ingresoCoincidente->inventario->sucursal->lugar;
                $row['cantidad'] = $ingresoCoincidente->cantidad_inicial;
                $row['cant_anterior'] = $cont == 0 ? $cantAudit : $row['cant_actual'];
                $row['cant_actual'] = $row['cant_anterior'] - $movimiento->cantidad_inicial;
                $row['fecha'] = date('d/m/Y', strtotime($movimiento->created_at));
                $results[$cont] = $row;
                $cont++;
            }
            //Aqui se verifica si contiene el id actual la collection de egresos anulados
            $egresoCoincidente = $movimientosEgresosAnulados->firstWhere('id', $movimiento->id);
            if ($egresoCoincidente !== null) {
                //Se ingresa el movimiento como anulado y se suma al inventario
                $row['id'] = $cont + 1;
                $row['detalle'] = $egresoCoincidente->inventario->detalle->descripcion;
                $row['num_transaccion'] = $egresoCoincidente->transaccion->id;
                $row['motivo'] = $egresoCoincidente->transaccion->motivo->nombre;
                $row['tipo'] = 'ANULACION';
                $row['sucursal'] = $egresoCoincidente->inventario->sucursal->lugar;
                $row['cantidad'] = $egresoCoincidente->cantidad_inicial;
                $row['cant_anterior'] = $cont == 0 ? $cantAudit : $row['cant_actual'];
                $row['cant_actual'] = $row['cant_anterior'] + $egresoCoincidente->cantidad_inicial;
                $row['fecha'] = date('d/m/Y', strtotime($movimiento->created_at));
                $results[$cont] = $row;
                $cont++;
            }
        }

        //Aqui se filtra los preingresos donde ha sido visto el ítem
        $results2 = ItemDetallePreingresoMaterial::where('detalle_id', $request->detalle_id)->get();
        $results2 = ItemDetallePreingresoMaterialResource::collection($results2);


        rsort($results); //aqui se ordena el array en forma descendente
        switch ($request->tipo_rpt) {
            case 'excel':
                try {
                    return Excel::download(new KardexExport(collect($results)), 'kardex.xlsx');
                } catch (Exception $ex) {
                    Log::channel('testing')->info('Log', ['ERROR', $ex->getMessage(), $ex->getLine()]);
                    throw ValidationException::withMessages([
                        'error' => [$ex->getMessage()],
                    ]);
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
                    throw ValidationException::withMessages([
                        'error' => [$ex->getMessage()],
                    ]);
                }
                break;
            default:
                return response()->json(compact('results', 'results2'));
        }
    }


    /**
     * Dashboard de bodega
     */
    public function dashboard(Request $request)
    {

        $results = $this->servicio->obtenerDashboard($request);

        return response()->json(compact('results'));
    }
}
