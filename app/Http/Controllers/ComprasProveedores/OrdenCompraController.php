<?php

namespace App\Http\Controllers\ComprasProveedores;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComprasProveedores\OrdenCompraRequest;
use App\Http\Resources\ComprasProveedores\OrdenCompraResource;
use App\Models\ComprasProveedores\OrdenCompra;
use App\Models\ComprasProveedores\PreordenCompra;
use App\Models\EstadoTransaccion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Src\Shared\Utils;

class OrdenCompraController extends Controller
{
    private $entidad = 'Orden de compra';
    public function __construct()
    {
        $this->middleware('can:puede.ver.ordenes_compras')->only('index', 'show');
        $this->middleware('can:puede.crear.ordenes_compras')->only('store');
        $this->middleware('can:puede.editar.ordenes_compras')->only('update');
        $this->middleware('can:puede.eliminar.ordenes_compras')->only('destroy');
    }

    /**
     * Listar
     */
    public function index(Request $request)
    {
        Log::channel('testing')->info('Log', ['Es empleado:', $request->all()]);
        $results = OrdenCompra::filter()->get();
        $results = OrdenCompraResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(OrdenCompraRequest $request)
    {
        $url = 'ordenes-compras';
        Log::channel('testing')->info('Log', ['Request recibida en ordenes de compras:', $request->all()]);
        try {
            DB::beginTransaction();
            //Adaptacion de foreign keys
            $datos = $request->validated();
            $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
            $datos['proveedor_id'] = $request->safe()->only(['proveedor'])['proveedor'];
            $datos['autorizador_id'] = $request->safe()->only(['autorizador'])['autorizador'];
            $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
            $datos['estado_id'] = $request->safe()->only(['estado'])['estado'];
            if ($request->preorden) $datos['preorden_id'] = $request->safe()->only(['preorden'])['preorden'];
            if ($request->pedido) $datos['pedido_id'] = $request->safe()->only(['pedido'])['pedido'];
            
            Log::channel('testing')->info('Log', ['Datos validados:', $datos]);
            if (count($request->categorias) == 0) {
                unset($datos['categorias']);
            }
            //Creación de la orden de compra
            $orden = OrdenCompra::create($datos);
            $modelo = new OrdenCompraResource($orden);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            OrdenCompra::guardarDetalles($orden, $request->listadoProductos);

            
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR store de ordenes de compras:', $e->getMessage(), $e->getLine()]);
            return response()->json(['ERROR' => $e->getMessage() . ', ' . $e->getLine()], 422);
        }
    }
}
