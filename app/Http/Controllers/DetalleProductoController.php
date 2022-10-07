<?php

namespace App\Http\Controllers;

use App\Http\Requests\DetalleProductoRequest;
use App\Http\Resources\DetalleProductoResource;
use App\Models\DetalleProducto;
use App\Models\DetallesProducto;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class DetalleProductoController extends Controller
{
    private $entidad = 'Detalle de producto';
    public function __construct()
    {
        $this->middleware('can:puede.ver.detalles')->only('index', 'show');
        $this->middleware('can:puede.crear.detalles')->only('store');
        $this->middleware('can:puede.editar.detalles')->only('update');
        $this->middleware('can:puede.eliminar.detalles')->only('destroy');
    }

    /**
     * Listar
     */
    public function index()
    {
        $results = DetalleProductoResource::collection(DetalleProducto::all());
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(DetalleProductoRequest $request)
    {
        // Log::channel('testing')->info('Log', ['Solicitud recibida:', $request->all()]);
        //Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['producto_id'] = $request->safe()->only(['producto'])['producto'];
        $datos['modelo_id'] = $request->safe()->only(['modelo'])['modelo'];
        $datos['span_id'] = $request->safe()->only(['span'])['span'];
        $datos['tipo_fibra_id'] = $request->safe()->only(['tipo_fibra'])['tipo_fibra'];
        $datos['hilo_id'] = $request->safe()->only(['hilos'])['hilos'];
        // Log::channel('testing')->info('Log', ['Datos adaptados:', $datos]);
        //Respuesta
        $modelo = DetalleProducto::create($datos);
        $modelo = new DetalleProductoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(DetalleProducto $detalle)
    {
        $modelo = new DetalleProductoResource($detalle);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(DetalleProductoRequest $request, DetalleProducto $detalle)
    {
        //Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['producto_id'] = $request->safe()->only(['producto'])['producto'];
        $datos['modelo_id'] = $request->safe()->only(['modelo'])['modelo'];
        $datos['span_id'] = $request->safe()->only(['span'])['span'];
        $datos['tipo_fibra_id'] = $request->safe()->only(['tipo_fibra'])['tipo_fibra'];
        $datos['hilo_id'] = $request->safe()->only(['hilos'])['hilos'];
        
        //Respuesta
        $detalle->update($datos);
        $modelo = new DetalleProductoResource($detalle->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(DetalleProducto $detalle)
    {
        $detalle->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
