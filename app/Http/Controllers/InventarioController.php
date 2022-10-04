<?php

namespace App\Http\Controllers;

use App\Http\Requests\InventarioRequest;
use App\Http\Resources\InventarioResource;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class InventarioController extends Controller
{
    private $entidad = 'Inventario';
    public function __construct()
    {
        $this->middleware('can:puede.ver.inventarios')->only('index', 'show');
        $this->middleware('can:puede.crear.inventarios')->only('store');
        $this->middleware('can:puede.editar.inventarios')->only('update');
        $this->middleware('can:puede.eliminar.inventarios')->only('update');
    }
    /**
     * Listar
     */
    public function index()
    {
        $results = InventarioResource::collection(Inventario::all());
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(InventarioRequest $request)
    {
        //Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['detalle_id']=$request->safe()->only(['detalle'])['detalle'];
        $datos['sucursal_id']=$request->safe()->only(['sucursal'])['sucursal'];
        $datos['condicion_id']=$request->safe()->only(['condicion'])['condicion'];
        $datos['cliente_id']=$request->safe()->only(['cliente'])['cliente'];
        //Respuesta
        $modelo = Inventario::create($datos);
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
        $datos['detalle_id']=$request->safe()->only(['detalle'])['detalle'];
        $datos['sucursal_id']=$request->safe()->only(['sucursal'])['sucursal'];
        $datos['condicion_id']=$request->safe()->only(['condicion'])['condicion'];
        $datos['cliente_id']=$request->safe()->only(['cliente'])['cliente'];
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
