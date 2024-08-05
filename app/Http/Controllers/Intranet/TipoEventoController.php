<?php

namespace App\Http\Controllers\Intranet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Intranet\TipoEventoRequest;
use App\Http\Resources\Intranet\TipoEventoResource;
use App\Models\Intranet\TipoEvento;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class TipoEventoController extends Controller
{
    private $entidad = 'Tipo de Evento';
    public function __construct()
    {
        $this->middleware('can:puede.ver.intra_tipos_eventos')->only('index', 'show');
        $this->middleware('can:puede.crear.intra_tipos_eventos')->only('store');
        $this->middleware('can:puede.editar.intra_tipos_eventos')->only('update');
        $this->middleware('can:puede.eliminar.intra_tipos_eventos')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = TipoEvento::filter()->orderBy('nombre', 'desc')->get();
        $results = TipoEventoResource::collection($results);

        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TipoEventoRequest $request)
    {
        //Respuesta
      $modelo = TipoEvento::create($request->validated());
      $modelo = new TipoEventoResource($modelo);
      $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

      return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(TipoEvento $tipo)
    {
        $modelo = new TipoEventoResource($tipo);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TipoEventoRequest $request, TipoEvento $tipo)
    {
         //Respuesta
         $tipo->update($request->validated());
         $modelo = new TipoEventoResource($tipo->refresh());
         $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

         return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipoEvento $tipo)
    {
        $tipo->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
