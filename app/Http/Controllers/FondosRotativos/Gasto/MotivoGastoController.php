<?php

namespace App\Http\Controllers\FondosRotativos\Gasto;

use App\Http\Controllers\Controller;
use App\Models\FondosRotativos\Gasto\MotivoGasto;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class MotivoGastoController extends Controller
{
    private $entidad = 'gasto';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = [];
        $results= MotivoGasto::all();
        return compact('results');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $datos = $request->all();
        $modelo = MotivoGasto::create($datos);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $results = [];
        $results = MotivoGasto::where('id', $id)->get();
        return response()->json($results);
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
        $datos = $request->all();
        $modelo = MotivoGasto::find($id);
        $modelo->update($datos);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modelo = MotivoGasto::find($id);
        $modelo->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje', 'modelo'));
    }
}
