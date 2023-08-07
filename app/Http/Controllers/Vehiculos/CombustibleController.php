<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehiculos\CombustibleRequest;
use App\Http\Resources\Vehiculos\CombustibleResource;
use App\Models\Vehiculos\Combustible;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class CombustibleController extends Controller
{
    private $entidad = 'Combustible';
    public function __construct()
    {
        $this->middleware('can:puede.ver.combustibles')->only('index', 'show');
        $this->middleware('can:puede.crear.combustibles')->only('store');
        $this->middleware('can:puede.editar.combustibles')->only('update');
        $this->middleware('can:puede.eliminar.combustibles')->only('destroy');
    }

    /**
     * Listar
     */
    public function index()
    {
        $results = Combustible::all();
        $results = CombustibleResource::collection($results);
        return response()->json(compact('results'));
    }
    /**
     * Guardar
     */
    public function store(CombustibleRequest $request)
    {
        //Respuesta
        $modelo = Combustible::create($request->validated());
        $modelo = new CombustibleResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store', 'M');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(Combustible $combustible)
    {
        $modelo = new CombustibleResource($combustible);
        return response()->json(compact('modelo'));
    }
    /**
     * Actualizar
     */
    public function update(CombustibleRequest $request, Combustible $combustible)
    {
        //Respuesta
        $combustible->update($request->validated());
        $modelo = new CombustibleResource($combustible->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update', 'M');

        return response()->json(compact('mensaje', 'modelo'));
    }
    /**
     * Eliminar
     */
    public function destroy(Combustible $combustible)
    {
        $combustible->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy', 'M');
        return response()->json(compact('mensaje'));
    }
}
