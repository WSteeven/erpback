<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmpleadoRequest;
use App\Http\Resources\EmpleadoResource;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class EmpleadoController extends Controller
{
    private $entidad = 'Empleado';

    /**
     * Listar
     */
    public function index()
    {
        $results = EmpleadoResource::collection(Empleado::all());
        return response()->json(compact('results'));
    }

/**
 * Guardar
 */
    public function store(EmpleadoRequest $request)
    {
        //Respuesta
        $modelo = Empleado::create($request->validated());
        $modelo = new EmpleadoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

/**
 * Consultar
 */
    public function show(Empleado $empleado)
    {
        $modelo = new EmpleadoResource($empleado);
        return response()->json(compact('modelo'));
    }

/**
 * Actualizar
 */
    public function update(EmpleadoRequest $request, Empleado  $empleado)
    {
        //Respuesta
        $empleado->update($request->validated());
        $modelo = new EmpleadoResource($empleado->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Eliminar
     */
    public function destroy(Empleado $empleado)
    {
        $empleado->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
