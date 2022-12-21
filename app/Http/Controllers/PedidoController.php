<?php

namespace App\Http\Controllers;

use App\Http\Requests\PedidoRequest;
use App\Http\Resources\PedidoResource;
use App\Models\Pedido;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class PedidoController extends Controller
{
    private $entidad = 'Pedido';
    public function __construct()
    {
        $this->middleware('can:puede.ver.pedidos')->only('index', 'show');
        $this->middleware('can:puede.crear.pedidos')->only('store');
        $this->middleware('can:puede.editar.pedidos')->only('update');
        $this->middleware('can:puede.eliminar.pedidos')->only('destroy');
    }

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $estado = $request['estado'];
        $results = [];

        if (auth()->user()->hasRole(User::ROL_BODEGA)) {
            $results = Pedido::all();
            PedidoResource::collection($results);
        } else {
            $results = Pedido::where('solicitante_id', auth()->user()->empleado->id)->where()->get();
            PedidoResource::collection($results);
        }


        $results = PedidoResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(PedidoRequest $request)
    {
        Log::channel('testing')->info('Log', ['Request recibida en pedido:', $request->all()]);
        try {
            DB::beginTransaction();
            // Adaptacion de foreign keys
            $datos = $request->validated();
            $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
            $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
            $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];
            $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];
            $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
            $datos['estado_id'] = $request->safe()->only(['estado'])['estado'];

            // Respuesta
            $pedido = Pedido::create($datos);
            $modelo = new PedidoResource($pedido);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            foreach ($request->listadoProductos as $listado) {
                $pedido->detalles()->attach($listado['id'], ['cantidad' => $listado['cantidad']]);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR del catch', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro'], 422);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(Pedido $pedido)
    {
        $modelo = new PedidoResource($pedido);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(PedidoRequest $request, Pedido $pedido)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
        $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
        $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];
        $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];
        $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
        $datos['estado_id'] = $request->safe()->only(['estado'])['estado'];

        // Respuesta
        $pedido->update($datos);
        $modelo = new PedidoResource($pedido->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(Pedido $pedido)
    {
        $pedido->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }


    /**
     * Consultar datos sin el método show.
     */
    public function showPreview(Pedido $pedido)
    {
        $modelo = new PedidoResource($pedido);

        return response()->json(compact('modelo'), 200);
    }

}
