<?php

namespace App\Http\Controllers;

use App\Http\Requests\HiloRequest;
use App\Http\Resources\HiloResource;
use App\Models\Hilo;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class HiloController extends Controller
{
    private $entidad = 'Hilo';
    public function __construct()
    {
        $this->middleware('can:puede.ver.hilos')->only('index', 'show');
        $this->middleware('can:puede.crear.hilos')->only('store');
        $this->middleware('can:puede.editar.hilos')->only('update');
        $this->middleware('can:puede.eliminar.hilos')->only('destroy');
    }

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $page = $request['page'];
        $results = [];
        
        if ($page) {
            $results = Hilo::simplePaginate($request['offset']);
            HiloResource::collection($results);
            $results->appends(['offset' => $request['offset']]);
        } else {
            $results =Hilo::all();
            HiloResource::collection($results);
        }
        return response()->json(compact('results'));
    }


    /**
     * Guardar
     */
    public function store(HiloRequest $request)
    {
        
        //Respuesta
        $modelo = Hilo::create($request->validated());
        $modelo = new HiloResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Consultar
     */
    public function show(Hilo $hilo)
    {
        $modelo = new HiloResource($hilo);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(HiloRequest $request, Hilo  $hilo)
    {
        //Respuesta
        $hilo->update($request->validated());
        $modelo = new HiloResource($hilo->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Eliminar
     */
    public function destroy(Hilo $hilo)
    {
        $hilo->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
