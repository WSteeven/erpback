<?php

namespace App\Http\Controllers\Intranet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Intranet\EventoRequest;
use App\Http\Resources\Intranet\EventoResource;
use App\Models\Intranet\Evento;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class EventoController extends Controller
{
    private $entidad = 'Evento';
    public function __construct()
    {
        $this->middleware('can:puede.ver.intra_eventos')->only('index', 'show');
        $this->middleware('can:puede.crear.intra_eventos')->only('store');
        $this->middleware('can:puede.editar.intra_eventos')->only('update');
        $this->middleware('can:puede.eliminar.intra_eventos')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = Evento::filter()->orderBy('titulo', 'desc')->get();
        $results = EventoResource::collection($results);

        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventoRequest $request)
    {
        //Respuesta
        $modelo = Evento::create($request->validated());
        $modelo = new EventoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Evento $evento)
    {
        $modelo = new EventoResource($evento);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EventoRequest $request, Evento $evento)
    {
        //Respuesta
        $evento->update($request->validated());
        $modelo = new EventoResource($evento->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Evento $evento)
    {
        $evento->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
