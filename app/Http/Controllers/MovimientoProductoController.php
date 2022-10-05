<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovimientoProductoRequest;
use App\Http\Resources\MovimientoProductoResource;
use App\Models\MovimientoProducto;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class MovimientoProductoController extends Controller
{
    private $entidad = 'Movimiento de Producto';
    public function __construct()
    {
        $this->middleware('can:puede.ver.movimientos_productos')->only('index', 'show');
        $this->middleware('can:puede.crear.movimientos_productos')->only('store');
        $this->middleware('can:puede.editar.movimientos_productos')->only('update');
        $this->middleware('can:puede.eliminar.movimientos_productos')->only('destroy');
    }

    /**
     * Listar
     */
    public function index()
    {
        $results = MovimientoProductoResource::collection(MovimientoProducto::all());
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(Request $request)
    {
        //Respuesta
        $modelo = MovimientoProducto::create($request->validated());
        $modelo = new MovimientoProductoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Consultar
     */
    public function show(MovimientoProducto $movimiento)
    {
        $modelo = new MovimientoProductoResource($movimiento);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(MovimientoProductoRequest $request, MovimientoProducto $movimiento)
    {
        //Respuesta
        $movimiento->update($request->validated());
        $modelo = new MovimientoProductoResource($movimiento->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(MovimientoProducto $movimiento)
    {
        $movimiento->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
