<?php

namespace App\Http\Controllers\FondosRotativos\Saldo;

use App\Http\Controllers\Controller;
use App\Http\Resources\FondosRotativos\Saldo\TipoSaldoResource;
use App\Models\FondosRotativos\Saldo\TipoSaldo;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class TipoSaldoController extends Controller
{
    private $entidad = 'tipo_saldo';
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $results = [];

        $results = TipoSaldo::ignoreRequest(['campos'])->filter()->get();
        $results = TipoSaldoResource::collection($results);

        return response()->json(compact('results'));
    }
      /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TipoSaldo  $tiposaldo
     * @return \Illuminate\Http\Response
     */
    public function store (Request $request)
    {


    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TipoSaldo  $tiposaldo
     * @return \Illuminate\Http\Response
     */
    public function update(TipoSaldo $request, TipoSaldo $activo)
    {

    }

    public function show(TipoSaldo $tiposaldo)
    {
        $modelo = new TipoSaldoResource($tiposaldo);
        return response()->json(compact('modelo'), 200);
    }

    public function destroy(TipoSaldo $tiposaldo)
    {
        $tiposaldo->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));

    }
}
