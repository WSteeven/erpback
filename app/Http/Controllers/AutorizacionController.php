<?php

namespace App\Http\Controllers;

use App\Http\Requests\AutorizacionRequest;
use App\Http\Resources\AutorizacionResource;
use App\Models\Autorizacion;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class AutorizacionController extends Controller
{
    private $entidad = 'Autorizacion';
    public function __construct()
    {
        $this->middleware('can:puede.ver.autorizaciones')->only('index', 'show');
        $this->middleware('can:puede.crear.autorizaciones')->only('store');
        $this->middleware('can:puede.editar.autorizaciones')->only('update');
        $this->middleware('can:puede.eliminar.autorizaciones')->only('update');

    }
    /**
     * Listar 
     */
    public function index(Request $request)
    {
        $page = $request['page'];
        $campos = explode(',', $request['campos']);
        $results = [];
        if($request['campos']){
            $results = Autorizacion::ignoreRequest(['campos'])->filter()->get($campos);
            return response()->json(compact('results'));    
        }else 
        if ($page) {
            $results = Autorizacion::simplePaginate($request['offset']);
            AutorizacionResource::collection($results);
            $results->appends(['offset' => $request['offset']]);
        } else {
            $results = Autorizacion::filter()->get();
        }
        AutorizacionResource::collection($results);
        return response()->json(compact('results'));
    }

/**
 * Guardar
 */
    public function store(AutorizacionRequest $request)
    {
        
        // Respuesta
        $modelo = Autorizacion::create($request->validated());
        $modelo = new AutorizacionResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

/**
 * Consultar
 */
    public function show(Autorizacion $autorizacion)
    {
        $modelo = new AutorizacionResource($autorizacion);
        return response()->json(compact('modelo'));
    }


    public function update(AutorizacionRequest $request, Autorizacion  $autorizacion)
    {
        //Respuesta
        $autorizacion->update($request->validated());
        $modelo = new AutorizacionResource($autorizacion->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('modelo', 'mensaje'));
    }

/**
 * Eliminar
 */
    public function destroy(Autorizacion $autorizacion)
    {
        $autorizacion->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
