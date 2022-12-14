<?php

namespace App\Http\Controllers;

use App\Http\Requests\TraspasoRequest;
use App\Http\Resources\TraspasoResource;
use App\Models\DetalleInventarioTraspaso;
use App\Models\DevolucionTraspaso;
use App\Models\Inventario;
use App\Models\MovimientoProducto;
use App\Models\Traspaso;
use App\Models\User;
use Dflydev\DotAccessData\Util;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class TraspasoController extends Controller
{
    private $entidad = 'Traspaso';
    public function __construct()
    {
        $this->middleware('can:puede.ver.traspasos')->only('index', 'show');
        $this->middleware('can:puede.crear.traspasos')->only('store');
        $this->middleware('can:puede.editar.traspasos')->only('update');
        $this->middleware('can:puede.eliminar.traspasos')->only('destroy');
    }

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $page = $request['page'];
        $estado = $request['estado'];
        $results = [];

        if ($page) {
            $results = Traspaso::where('devuelta', $estado)->simplePaginate($request['offset']);
            TraspasoResource::collection($results);
            $results->appends(['offset' => $request['offset']]);
        } else {
            $results = Traspaso::all();
            TraspasoResource::collection($results);
        }
        return response()->json(compact('results'));
    }
    /**
     * Guardar
     */
    public function store(TraspasoRequest $request)
    {
        Log::channel('testing')->info('Log', ['Request recibida:', $request->all()]);

        // if (auth()->user()->hasRole(User::ROL_BODEGA)) {
        // Log::channel('testing')->info('Log', ['Pasó la validacion, solo los bodegueros pueden hacer traspasos en su sucursal']);
        try {
            // Log::channel('testing')->info('Log', ['Datos validados', $datos]);
            DB::beginTransaction();
            $datos = $request->validated();

            //Adaptación de foreign keys
            $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
            $datos['desde_cliente_id'] = $request->safe()->only(['desde_cliente'])['desde_cliente'];
            $datos['hasta_cliente_id'] = $request->safe()->only(['hasta_cliente'])['hasta_cliente'];
            $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
            $datos['estado_id'] = $request->safe()->only(['estado'])['estado'];
            $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];

            //Respuesta
            $traspaso = Traspaso::create($datos);
            $modelo = new TraspasoResource($traspaso);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            //Guardamos los productos seleccionados en el detalle
            foreach ($request->listadoProductos as $listado) {
                $traspaso->items()->attach($listado['id'], ['cantidad' => $listado['cantidades']]);
            }

            Inventario::traspasarProductos($request->sucursal, $request->desde_cliente, $traspaso, $request->hasta_cliente, $request->listadoProductos);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR del catch', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro'], 422);
        }
        return response()->json(compact('mensaje', 'modelo'));
        // } else return response()->json(compact('Este usuario no puede realizar préstamo de materiales'), 421);
    }

    /**
     * Consultar
     */
    public function show(Traspaso $traspaso)
    {
        $modelo = new TraspasoResource($traspaso);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(TraspasoRequest $request, Traspaso $traspaso)
    {
        Log::channel('testing')->info('Log', ['Request recibida en el update:', $request->all()]);
        try {
            DB::beginTransaction();
            $datos = $request->validated();

            //Adaptación de foreign keys
            $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
            $datos['desde_cliente_id'] = $request->safe()->only(['desde_cliente'])['desde_cliente'];
            $datos['hasta_cliente_id'] = $request->safe()->only(['hasta_cliente'])['hasta_cliente'];
            $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
            $datos['estado_id'] = $request->safe()->only(['estado'])['estado'];
            $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];

            Log::channel('testing')->info('Log', ['Datos validados en el update:', $request->validated()]);

            $completa = false;
            foreach ($request->listadoProductos as $listado) {
                // Log::channel('testing')->info('Log', ['Entró al primer foreach traspaso items:', $traspaso->items,$traspaso->items->cantidad]);

                if (!is_null($listado['devolucion'])) {
                    Log::channel('testing')->info('Log', ['Entró al segundo foreach:', $listado]);
                    $item = Inventario::where('id', $listado['id'])->first();
                    Log::channel('testing')->info('Log', ['Entró al item:', $item]);
                    $detalle = DetalleInventarioTraspaso::where('traspaso_id', $traspaso->id)->where('inventario_id', $listado['id'])->first();
                    $devolucion = new DevolucionTraspaso(['cantidad' => $listado['devolucion']]);
                    // $movimiento = new MovimientoProducto([
                    //     'cantidad' => $listado['devolucion'],
                    //     'precio_unitario' => is_null($detalle->precio_compra) ? 0 : $detalle->precio_compra,
                    //     'saldo' => $item->cantidad - $listado['devolucion'],
                    //     'bodeguero_id' => auth()->user()->empleado->id,
                    // ]);
                    // $detalle->movimientos()->save($movimiento);
                    Log::channel('testing')->info('Log', ['Detalle y devoluciones?:', $detalle, $detalle->devoluciones(), $detalle->devoluciones]);
                    $detalle->devoluciones()->save($devolucion);
                }
            }

            Inventario::devolverProductos($request->sucursal, $request->hasta_cliente, $request->listadoProductos);

            //Respuesta
            $traspaso->update($datos);
            $modelo = new TraspasoResource($traspaso->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR del catch en traspaso update', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar el registro ' . $e->getLine()], 422);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }
}
