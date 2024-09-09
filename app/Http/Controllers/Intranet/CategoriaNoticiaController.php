<?php

namespace App\Http\Controllers\Intranet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Intranet\CategoriaNoticiaRequest;
use App\Http\Resources\Intranet\CategoriaNoticiaResource;
use App\Models\Intranet\CategoriaNoticia;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class CategoriaNoticiaController extends Controller
{
    private $entidad = 'Categoria';
    public function __construct()
    {
        $this->middleware('can:puede.ver.intra_categorias')->only('index', 'show');
        $this->middleware('can:puede.crear.intra_categorias')->only('store');
        $this->middleware('can:puede.editar.intra_categorias')->only('update');
        $this->middleware('can:puede.eliminar.intra_categorias')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = CategoriaNoticia::filter()->orderBy('nombre', 'desc')->get();
        $results = CategoriaNoticiaResource::collection($results);

        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoriaNoticiaRequest $request)
    {
        //Respuesta
        $modelo = CategoriaNoticia::create($request->validated());
        $modelo = new CategoriaNoticiaResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(CategoriaNoticia $categoria)
    {
        $modelo = new CategoriaNoticiaResource($categoria);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoriaNoticiaRequest $request, CategoriaNoticia $categoria)
    {
        //Respuesta
        $categoria->update($request->validated());
        $modelo = new CategoriaNoticiaResource($categoria->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CategoriaNoticia $categoria)
    {
        $categoria->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
