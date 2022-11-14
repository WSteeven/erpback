<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpanRequest;
use App\Models\Span;
use App\Http\Resources\SpanResource;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class SpanController extends Controller
{

    private $entidad = 'Span';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = $request['page'];
        $results = [];
        
        if ($page) {
            $results = Span::simplePaginate($request['offset']);
            SpanResource::collection($results);
            $results->appends(['offset' => $request['offset']]);
        } else {
            $results = Span::all();
            SpanResource::collection($results);
        }
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSpanRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SpanRequest $request)
    {
        //Respuesta
        $modelo = Span::create($request->validated());
        $modelo = new SpanResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Span  $span
     * @return \Illuminate\Http\Response
     */
    public function show(Span $span)
    {
        $modelo = new SpanResource($span);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSpanRequest  $request
     * @param  \App\Models\Span  $span
     * @return \Illuminate\Http\Response
     */
    public function update(SpanRequest $request, Span $span)
    {
         //Respuesta
         $span->update($request->validated());
         $modelo = new SpanResource($span->refresh());
         $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
 
         return response()->json(compact('modelo', 'mensaje'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Span  $span
     * @return \Illuminate\Http\Response
     */
    public function destroy(Span $span)
    {
        $span->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
