<?php

namespace App\Http\Controllers;

use App\Http\Requests\UnidadMedidaRequest;
use App\Http\Resources\UnidadMedidaResource;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class UnidadMedidaController extends Controller
{
    private $entidad = 'Unidad de medida';
    // public function __construct()
    // {
    //     $this->middleware('can:puede.ver.unidades_medidas')->only('index', 'show');
    //     $this->middleware('can:puede.crear.unidades_medidas')->only('store');
    //     $this->middleware('can:puede.editar.unidades_medidas')->only('update');
    //     $this->middleware('can:puede.eliminar.unidades_medidas')->only('update');
    // }

    /**
     * Listar
     */
    public function index(){
        $results = UnidadMedida::all();
        $results = UnidadMedidaResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(UnidadMedidaRequest $request){
        //Respuesta
        $modelo = UnidadMedida::create($request->validated());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(UnidadMedida $unidad){
        $modelo = new UnidadMedidaResource($unidad);
        return response()->json(compact('modelo'));
    }
    /**
     * Actualizar
     */
    public function update(UnidadMedidaRequest $request, UnidadMedida $unidad){
        //Respuesta
        $unidad->update($request->validated());
        $modelo = new UnidadMedidaResource($unidad->refresh());
        $mensaje  = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(UnidadMedida $unidad){
        $unidad->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }


}
