<?php

namespace App\Http\Controllers;

use App\Http\Requests\InventarioRequest;
use App\Http\Resources\InventarioResource;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class InventarioController extends Controller
{
    private $entidad = 'Inventario';
    public function __construct()
    {
        $this->middleware('can:puede.ver.inventarios')->only('index', 'show');
        $this->middleware('can:puede.crear.inventarios')->only('store');
        $this->middleware('can:puede.editar.inventarios')->only('update');
        $this->middleware('can:puede.eliminar.inventarios')->only('destroy');
    }
    /**
     * Listar
     */
    public function index(Request $request)
    {
        $page = $request['page'];
        $sucursal = $request['sucursal'];
        $results = [];
        if ($page) {
            $results = Inventario::simplePaginate($request['offset']);
            InventarioResource::collection($results);
            $results->appends(['offset' => $request['offset']]);
        } else {
            $results = Inventario::all();
            // $results = Inventario::filter()->get();
            $results = InventarioResource::collection($results);
        }
        if($sucursal){
            $results = Inventario::where('sucursal_id', $sucursal)->get();
            $results = InventarioResource::collection($results);
        }
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
        Log::channel('testing')->info('Log', ['listado', $request->all()]);
        $item = Inventario::where('detalle_id', $request->detalle_id)
            ->where('sucursal_id', $request->sucursal_id)
            ->where('cliente_id', $request->cliente_id)
            ->where('condicion_id', $request->condicion)
            ->first();
        Log::channel('testing')->info('Log', ['item encontrado', $item]);
        if($item){
            $datos['cantidad']=$request->cantidad+$item->cantidad;
            $item->update($datos);
            $modelo=$item->refresh();
        }else{
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
}
