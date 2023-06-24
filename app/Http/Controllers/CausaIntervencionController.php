<?php

namespace App\Http\Controllers;

use App\Models\CausaIntervencion;
use Illuminate\Http\Request;

class CausaIntervencionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = CausaIntervencion::all();
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
     * @param  \App\Models\CausaIntervencion  $causaIntervencion
     * @return \Illuminate\Http\Response
     */
    public function show(CausaIntervencion $causaIntervencion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CausaIntervencion  $causaIntervencion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CausaIntervencion $causaIntervencion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CausaIntervencion  $causaIntervencion
     * @return \Illuminate\Http\Response
     */
    public function destroy(CausaIntervencion $causaIntervencion)
    {
        //
    }
}
