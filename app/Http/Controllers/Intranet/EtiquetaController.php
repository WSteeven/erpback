<?php

namespace App\Http\Controllers\Intranet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Intranet\EtiquetaRequest;
use App\Http\Resources\Intranet\EtiquetaResource;
use App\Models\Intranet\Etiqueta;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class EtiquetaController extends Controller
{
    private $entidad = 'Etiqueta';
    public function __construct()
    {
        $this->middleware('can:puede.ver.intra_etiquetas')->only('index', 'show');
        $this->middleware('can:puede.crear.intra_etiquetas')->only('store');
        $this->middleware('can:puede.editar.intra_etiquetas')->only('update');
        $this->middleware('can:puede.eliminar.intra_etiquetas')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = Etiqueta::filter()->orderBy('nombre', 'desc')->get();
        $results = EtiquetaResource::collection($results);

        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EtiquetaRequest $request)
    {
      //Respuesta
      $modelo = Etiqueta::create($request->validated());
      $modelo = new EtiquetaResource($modelo);
      $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

      return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Etiqueta $etiqueta)
    {
        $modelo = new EtiquetaResource($etiqueta);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EtiquetaRequest $request, Etiqueta $etiqueta)
    {
        //Respuesta
        $etiqueta->update($request->validated());
        $modelo = new EtiquetaResource($etiqueta->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Etiqueta $etiqueta)
    {
        $etiqueta->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
