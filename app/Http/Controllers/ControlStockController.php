<?php

namespace App\Http\Controllers;

use App\Http\Requests\ControlStockRequest;
use App\Http\Resources\ControlStockResource;
use App\Models\ControlStock;
use Illuminate\Http\Request;
use League\CommonMark\Extension\Mention\MentionParser;
use Src\Shared\Utils;

class ControlStockController extends Controller
{
    private $entidad = 'Control de Stock';
    public function __construct()
    {
        $this->middleware('can:puede.ver.control_stock')->only('index', 'show');
        $this->middleware('can:puede.crear.control_stock')->only('store');
        $this->middleware('can:puede.editar.control_stock')->only('update');
        $this->middleware('can:puede.eliminar.control_stock')->only('update');
    }

    /**
     * Listar
     */
    public function index()
    {
        $results = ControlStockResource::collection(ControlStock::all());
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(ControlStockRequest $request)
    {
        //Adaptacion de foreign keys
        $datos = $request->validated();
        // $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
        // $datos['detalle_id'] = $request->safe()->only(['detalle'])['detalle'];
        // $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];

        //Respuesta
        $modelo = ControlStock::create($datos);
        $modelo = new ControlStockResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(ControlStock $controlStock)
    {
        $modelo = new ControlStockResource($controlStock);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(ControlStockRequest $request, ControlStock $controlStock)
    {
        //Adaptacion de foreign keys
        $datos = $request->validated();
        // $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
        // $datos['detalle_id'] = $request->safe()->only(['detalle'])['detalle'];
        // $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];

        //Respuesta
        $controlStock->update($datos);
        $modelo = new ControlStockResource($controlStock);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(ControlStock $controlStock)
    {
        $controlStock->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
