<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartamentoRequest;
use App\Http\Resources\DepartamentoResource;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class DepartamentoController extends Controller
{
    private $entidad = 'Departamento';

    public function listar()
    {
        $campos = explode(',', request('campos'));

        if (request('campos')) {
            return Departamento::ignoreRequest(['campos'])->filter()->latest()->get($campos);
        } else {
            return DepartamentoResource::collection(Departamento::filter()->latest()->get());
        }
    }

    /*********
     * Listar
     *********/
    public function index(Request $request)
    {
        Log::channel('testing')->info('Log', ['Request recibida:', $request->all()]);
        $results = $this->listar();
        return response()->json(compact('results'));
    }


    /**********
     * Guardar
     **********/
    public function store(DepartamentoRequest $request)
    {
        //Respuesta
        $modelo = Departamento::create($request->validated());
        $modelo = new DepartamentoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /************
     * Consultar
     ************/
    public function show(Departamento $departamento)
    {
        $modelo = new DepartamentoResource($departamento);
        return response()->json(compact('modelo'));
    }


    /*************
     * Actualizar
     *************/
    public function update(DepartamentoRequest $request, Departamento  $departamento)
    {
        $datos = $request->validated();
        $datos['responsable_id'] = $datos['responsable'];

        // Respuesta
        $departamento->update($datos);
        $modelo = new DepartamentoResource($departamento->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }
}
