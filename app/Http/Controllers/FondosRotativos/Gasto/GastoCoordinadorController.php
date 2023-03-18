<?php

namespace App\Http\Controllers\FondosRotativos\Gasto;

use App\Http\Controllers\Controller;
use App\Models\FondosRotativos\Gasto\GastoCoordinador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Src\Shared\Utils;

class GastoCoordinadorController extends Controller
{
    private $entidad = 'gasto_coordinador';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = [];
        $results= GastoCoordinador::all();
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
        $datos['fecha_gasto'] = date('Y-m-d');
        $datos['id_motivo'] = $request->motivo;
        $datos['id_lugar'] = $request->lugar;
        //usuario autenticado
        $user = Auth::user();
        $datos['id_usuario'] = $user->id ;
        $modelo = GastoCoordinador::create($datos);
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
        $results = GastoCoordinador::where('id', $id)->get();
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
        $modelo = GastoCoordinador::find($id);
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
        $modelo = GastoCoordinador::find($id);
        $modelo->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje', 'modelo'));
    }
}
