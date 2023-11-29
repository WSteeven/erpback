<?php

namespace App\Http\Controllers\RecursosHumanos;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\BancoRequest;
use App\Http\Resources\RecursosHumanos\BancoResource;
use App\Models\RecursosHumanos\Banco;
use Src\Shared\Utils;

class BancoController extends Controller
{
    private $entidad = 'Banco';
    public function __construct()
    {
        $this->middleware('can:puede.ver.bancos')->only('index', 'show');
        $this->middleware('can:puede.crear.bancos')->only('store');
        $this->middleware('can:puede.editar.bancos')->only('update');
        $this->middleware('can:puede.eliminar.bancos')->only('update');
    }

    public function index()
    {
        $results = [];
        $results = Banco::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(BancoRequest $request)
    {
        $banco = Banco::create($request->validated());
        $modelo = new BancoResource($banco);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(Banco $banco)
    {
        $modelo = new BancoResource($banco);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(BancoRequest $request, Banco $banco)
    {
        $banco->update($request->validated());
        $modelo = new BancoResource($banco->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(Banco $banco)
    {
        $banco->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');

        return response()->json(compact('mensaje'));
    }
}
