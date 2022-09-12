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
        $results = DetallesProductoResource::collectiond(DetallesProducto::all());
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(DetallesProductoRequest $request)
    {
        //Respuesta
        $modelo = DetallesProducto::create($request->validated());
        $modelo = new DetallesProductoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(DetallesProducto $detalleProducto)
    {
        $modelo = new DetallesProductoResource($detalleProducto);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(DetallesProductoRequest $request, DetallesProducto $detalleProducto)
    {
        //Respuesta
        $detalleProducto->update($request->validated());
        $modelo = new DetallesProductoResource($detalleProducto->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(DetallesProducto $detalleProducto)
    {
        $detalleProducto->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
