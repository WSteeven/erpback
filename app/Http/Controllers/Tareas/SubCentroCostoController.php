<?php

namespace App\Http\Controllers\Tareas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tareas\SubCentroCostoRequest;
use App\Http\Resources\Tareas\SubCentroCostoResource;
use App\Models\Tareas\SubcentroCosto;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class SubCentroCostoController extends Controller
{
    private $entidad = 'Subcentro de Costos';
    public function __construct()
    {
        $this->middleware('can:puede.ver.subcentros_costos')->only('index', 'show');
        $this->middleware('can:puede.crear.subcentros_costos')->only('store');
        $this->middleware('can:puede.editar.subcentros_costos')->only('update');
        $this->middleware('can:puede.eliminar.subcentros_costos')->only('update');
    }

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $results = SubcentroCosto::filter()->orderBy('id', 'desc')->get();
        $results = SubCentroCostoResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(SubCentroCostoRequest $request)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['centro_costo_id'] = $request->safe()->only(['centro_costo'])['centro_costo'];
        $datos['grupo_id'] = $request->safe()->only(['grupo'])['grupo'];

        // Respuesta
        $modelo = SubcentroCosto::create($datos);
        $modelo = new SubCentroCostoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(SubcentroCosto $subcentro)
    {
        $modelo = new SubCentroCostoResource($subcentro);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(SubCentroCostoRequest $request, SubcentroCosto  $subcentro)
    {
        //Adaptacion de foreign keys 
        $datos = $request->validated();
        $datos['centro_costo_id'] = $request->safe()->only(['centro_costo'])['centro_costo'];
        $datos['grupo_id'] = $request->safe()->only(['grupo'])['grupo'];
        //Respuesta
        $subcentro->update($datos);
        $modelo = new SubCentroCostoResource($subcentro->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(SubcentroCosto $subcentro)
    {
        $subcentro->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
