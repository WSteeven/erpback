<?php

namespace App\Http\Controllers;

use App\Http\Requests\PisoRequest;
use App\Http\Resources\PisoResource;
use App\Models\Piso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Src\Shared\Utils;

class PisoController extends Controller
{
    private $entidad = 'Piso';

    /**
     * Listar
     */
    public function index()
    {
        $results = PisoResource::collection(Piso::all());
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(PisoRequest $request)
    {
        //Respuesta
        $modelo = Piso::create($request->validated());
        $modelo = new PisoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(Piso $piso)
    {
        $modelo = new PisoResource($piso);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(PisoRequest $request, Piso  $piso)
    {        
        //Respuesta
        $piso->update($request->validated());
        $modelo = new PisoResource($piso->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
        
    }

    /**
     * Eliminar
     */
    public function destroy(Piso $piso)
    {
        $piso->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
