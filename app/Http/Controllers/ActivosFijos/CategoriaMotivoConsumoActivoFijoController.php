<?php

namespace App\Http\Controllers\ActivosFijos;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActivosFijos\CategoriaMotivoConsumoActivoFijoResource;
use App\Models\ActivosFijos\CategoriaMotivoConsumoActivoFijo;
use Illuminate\Http\Request;

class CategoriaMotivoConsumoActivoFijoController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:puede.ver.categorias_motivos_consumo_activos_fijos')->only('index', 'show');
        $this->middleware('can:puede.crear.categorias_motivos_consumo_activos_fijos')->only('store');
        $this->middleware('can:puede.editar.categorias_motivos_consumo_activos_fijos')->only('update');
        $this->middleware('can:puede.eliminar.categorias_motivos_consumo_activos_fijos')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = CategoriaMotivoConsumoActivoFijo::ignoreRequest(['campos'])->filter()->get();
        $results = CategoriaMotivoConsumoActivoFijoResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
