<?php

namespace App\Http\Controllers;

use App\Events\NewMessagePruebaEvent;
use App\Events\PruebaEvent;
use App\Http\Requests\CategoriaRequest;
use App\Http\Resources\CategoriaResource;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Src\Shared\Utils;

class CategoriaController extends Controller
{
    private $entidad = 'Categoria';
    public function __construct()
    {
        $this->middleware('can:puede.ver.categorias')->only('index', 'show');
        $this->middleware('can:puede.crear.categorias')->only('store');
        $this->middleware('can:puede.editar.categorias')->only('update');
        $this->middleware('can:puede.eliminar.categorias')->only('destroy');
    }

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $page = $request['page'];
        $campos = explode(',', $request['campos']);
        $results = [];
        if ($request['campos']) {
            $results = Categoria::ignoreRequest(['campos'])->filter()->get($campos);
            return response()->json(compact('results'));
        } else
        if ($page) {
            $results = Categoria::simplePaginate($request['offset']);
        } else {
            $results = Categoria::filter()->get();
        }
        CategoriaResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(CategoriaRequest $request)
    {
        //Respuesta
        $modelo = Categoria::create($request->validated());
        $modelo = new CategoriaResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        // event(new PruebaEvent("Se ha creado una categoria nueva"));
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(Categoria $categoria)
    {
        $modelo = new CategoriaResource($categoria);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(CategoriaRequest $request, Categoria  $categoria)
    {
        //Respuesta
        $categoria->update($request->validated());
        $modelo = new CategoriaResource($categoria->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
