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
        Log::channel('testing')->info('Log', ['Request recibida en productos', $request->all()]);
        $page = $request['page'];
        $campos = explode(',', $request['campos']);
        $results = [];
        if ($request['campos']) {
            $results = Producto::all($campos);
            $results =ProductoResource::collection($results);
            return response()->json(compact('results'));
        } else if($request->categoria_id && is_null($request->categoria_id[0])){
            if($request['search']){
                $results = Producto::search($request['search'])->get();
                Log::channel('testing')->info('Log', ['entro en searcgh', $results]);
            }else{
                $results = Producto::ignoreRequest(['campos', 'categoria_id'])->filter()->get();
                Log::channel('testing')->info('Log', ['entro en if 42', $results]);
            }
        }else{
            if($request->search){
                // $results = Producto::ignoreRequest(['campos','search'])->filter()->search($request['search'])->get();
                $results = Producto::search($request->search)
                ->when($request->categoria_id, function($query) use ($request){
                    return $query->whereIn('categoria_id', $request->categoria_id);
                })->get();
                Log::channel('testing')->info('Log', ['entro en else 52', $results]);
            }else{
                $results = Producto::ignoreRequest(['campos'])->filter()->get();
                Log::channel('testing')->info('Log', ['entro en else 50', $results]);
            }
        }
        $results = ProductoResource::collection($results);
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
        $datos['unidad_medida_id'] = $request->safe()->only(['unidad_medida'])['unidad_medida'];

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
        $datos['unidad_medida_id'] = $request->safe()->only(['unidad_medida'])['unidad_medida'];

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
