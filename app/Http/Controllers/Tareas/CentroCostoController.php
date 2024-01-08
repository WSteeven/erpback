<?php

namespace App\Http\Controllers\Tareas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tareas\CentroCostoRequest;
use App\Http\Resources\Tareas\CentroCostoResource;
use App\Models\Tareas\CentroCosto;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class CentroCostoController extends Controller
{
    private $entidad = 'Centro de Costos';
    public function __construct()
    {
        $this->middleware('can:puede.ver.centros_costos')->only('index', 'show');
        $this->middleware('can:puede.crear.centros_costos')->only('store');
        $this->middleware('can:puede.editar.centros_costos')->only('update');
        $this->middleware('can:puede.eliminar.centros_costos')->only('update');
    }

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $results = CentroCosto::filter()->orderBy('id', 'desc')->get();
        $results = CentroCostoResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(CentroCostoRequest $request)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];

        // Respuesta
        $modelo = CentroCosto::create($datos);
        $modelo = new CentroCostoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(CentroCosto $centro){
        $modelo = new CentroCostoResource($centro);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(CentroCostoRequest $request, CentroCosto  $centro)
    {
        //Adaptacion de foreign keys 
        $datos = $request->validated();
        $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];
        //Respuesta
        $centro->update($datos);
        $modelo = new CentroCostoResource($centro->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(CentroCosto $centro)
    {
        $centro->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
