<?php

namespace App\Http\Controllers\ActivosFijos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Src\App\ActivosFijos\SeguimientoConsumoActivosFijosService;

class SeguimientoConsumoActivosFijosController extends Controller
{
    private SeguimientoConsumoActivosFijosService $seguimientoConsumoActivosFijosService;

    public function __construct()
    {
        $this->seguimientoConsumoActivosFijosService = new SeguimientoConsumoActivosFijosService();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /* request()->validate([
            'detalle_producto_id' => 'required|numeric|integer|exists:detalles_productos,id',
            'cliente_id' => 'required|numeric|integer|exists:clientes,id',
            'resumen' => 'nullable|boolean',
            'seguimiento' => 'nullable|boolean',
        ]); */

        $results = $this->seguimientoConsumoActivosFijosService->seguimientoConsumoActivosFijos();
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
