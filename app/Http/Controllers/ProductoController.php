<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductoRequest;
use App\Http\Resources\ProductoResource;
use App\Http\Resources\TipoTareaResource;
use App\Models\Producto;
use App\Models\TipoTrabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class ProductoController extends Controller
{
    private $entidad = 'Producto';
    public function __construct()
    {
        $this->middleware('can:puede.ver.productos')->only('index', 'show');
        $this->middleware('can:puede.crear.productos')->only('store');
        $this->middleware('can:puede.editar.productos')->only('update');
        $this->middleware('can:puede.eliminar.productos')->only('destroy');
    }
    /**
     * Listar
     */
    public function index(Request $request)
    {
        $page = $request['page'];
        $results = [];
        
        if ($page) {
            $results = Producto::simplePaginate($request['offset']);
            ProductoResource::collection($results);
            $results->appends(['offset' => $request['offset']]);
        } else {
            $results = Producto::all();
            // Log::channel('testing')->info('Log', ['Listado solicitado', $results]);
            $results = ProductoResource::collection($results);
            // Log::channel('testing')->info('Log', ['Listado solicitado pasado por el resource', $results]);
        }
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(ProductoRequest $request)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['categoria_id'] = $request->safe()->only(['categoria'])['categoria'];

        // Respuesta
        $modelo = Producto::create($datos);
        $modelo = new ProductoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(Producto $producto)
    {
        $modelo = new ProductoResource($producto);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(ProductoRequest $request, Producto  $producto)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['categoria_id'] = $request->safe()->only(['categoria'])['categoria'];

        // Respuesta
        $producto->update($datos);
        $modelo = new ProductoResource($producto->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('modelo', 'mensaje'));
    } 

    /**
     * Eliminar
     */
    public function destroy(Producto $producto)
    {
        $producto->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
