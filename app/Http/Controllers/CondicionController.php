<?php

namespace App\Http\Controllers;

use App\Http\Requests\CondicionRequest;
use App\Http\Resources\CondicionResource;
use App\Models\Condicion;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class CondicionController extends Controller
{
    private $entidad = 'Condicion';
    public function __construct()
    {
        $this->middleware('can:puede.ver.condiciones')->only('index', 'show');
        $this->middleware('can:puede.crear.condiciones')->only('store');
        $this->middleware('can:puede.editar.condiciones')->only('update');
        $this->middleware('can:puede.eliminar.condiciones')->only('update');
    }
    /** Listar*/
    public function index()
    {
        $results = CondicionResource::collection(Condicion::all());
        return response()->json(compact('results'));
    }

/**
 * Guardar
 */
    public function store(CondicionRequest $request)
    {
        //Respuesta
        $modelo = Condicion::create($request->validated());
        $modelo = new CondicionResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

/**
 * Consultar
 */
    public function show(Condicion $condicion)
    {
        $modelo = new CondicionResource($condicion);
        return response()->json(compact('modelo'));
    }

/**
 * Actualizar
 */
    public function update(CondicionRequest $request, Condicion  $condicion)
    {
        $request->validate(['nombre' => 'required|unique:condiciones_de_productos']);
        //Respuesta
        $condicion->update($request->validated());
        $modelo = new CondicionResource($condicion->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Eliminar
     */
    public function destroy(Condicion $condicion)
    {
        $condicion->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
