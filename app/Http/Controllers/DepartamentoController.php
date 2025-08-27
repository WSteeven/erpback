<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecursosHumanos\DepartamentoRequest;
use App\Http\Resources\DepartamentoResource;
use App\Models\Departamento;
use Src\Shared\Utils;

class DepartamentoController extends Controller
{
    private string $entidad = 'Departamento';

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
    public function index()
    {
        // Log::channel('testing')->info('Log', ['Request recibida:', $request->all()]);
        $results = $this->listar();
        return response()->json(compact('results'));
    }


    /**********
     * Guardar
     **********/
    public function store(DepartamentoRequest $request)
    {
        $datos = $request->validated();
        $datos['responsable_id'] = $datos['responsable'];

        //Respuesta
        $modelo = Departamento::create($datos);
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

    /*************
     * Consultar Departamentos con empleados activos
     ************ */
    public function departamentosConEmpleados()
    {
        $departamentos = Departamento::withCount([
            'empleados as cantidad_empleados' => function ($query) {
                $query->where('estado', 1); // solo empleados activos
            }
        ])
            ->where('activo', 1) // solo departamentos activos
            ->where('id', '!=', 9) // excluir id 9, según tu lógica
            ->having('cantidad_empleados', '>', 0)
            ->get();

        return response()->json($departamentos);
    }
}
