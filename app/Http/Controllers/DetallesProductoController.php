<?php

namespace App\Http\Controllers;

use App\Http\Requests\DetallesProductoRequest;
use App\Http\Resources\DetallesProductoResource;
use App\Models\CodigoCliente;
use App\Models\DetallesProducto;
use App\Models\Propietario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class DetallesProductoController extends Controller
{
    private $entidad = 'Detalle de producto';
    /* public function __construct()
    {
        $this->middleware('can:puede.ver.DetallesProductos')->only('index', 'show');
        $this->middleware('can:puede.crear.DetallesProductos')->only('store');
        $this->middleware('can:puede.editar.DetallesProductos')->only('update');
        $this->middleware('can:puede.eliminar.DetallesProductos')->only('destroy');

    } */

    /**
     * Listar
     */
    public function index()
    {
        $results = DetallesProductoResource::collection(DetallesProducto::all());
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(DetallesProductoRequest $request)
    {
        Log::channel('testing')->info('Log', ['Solicitud recibida:', $request->all()]);
        //Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['producto_id'] = $request->safe()->only(['producto'])['producto'];
        $datos['modelo_id'] = $request->safe()->only(['modelo'])['modelo'];
        $datos['tipo_fibra_id'] = $request->safe()->only(['tipo_fibra'])['tipo_fibra'];
        $datos['hilo_id'] = $request->safe()->only(['hilos'])['hilos'];
        Log::channel('testing')->info('Log', ['Datos adaptados:', $datos]);
        //Respuesta
        $modelo = DetallesProducto::create($datos);
        $modelo = new DetallesProductoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(DetallesProducto $detalle)
    {
        $modelo = new DetallesProductoResource($detalle);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(DetallesProductoRequest $request, DetallesProducto $detalle)
    {
        //Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['producto_id'] = $request->safe()->only(['producto'])['producto'];
        $datos['modelo_id'] = $request->safe()->only(['modelo'])['modelo'];
        $datos['tipo_fibra_id'] = $request->safe()->only(['tipo_fibra'])['tipo_fibra'];
        $datos['hilo_id'] = $request->safe()->only(['hilos'])['hilos'];
        
        //Respuesta
        $detalle->update($datos);
        $modelo = new DetallesProductoResource($detalle->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(DetallesProducto $detalle)
    {
        $detalle->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
