<?php

namespace App\Http\Controllers;

use App\Http\Requests\RamRequest;
use App\Http\Resources\RamResource;
use App\Models\Ram;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class RamController extends Controller
{
    private $entidad = 'Memoria';

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $results = Ram::all();
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(RamRequest $request)
    {
        //Respuesta 
        $modelo = Ram::create($request->validated());
        $modelo = new RamResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(Ram $ram)
    {
        $modelo = new RamResource($ram);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */

    public function update(RamRequest $request, Ram $ram)
    {
        //Respuesta
        $ram->update($request->validated());
        $modelo = new RamResource($ram->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(Ram $ram)
    {
        $ram->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');

        return response()->json(compact('mensaje'));
    }
}
