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
    public function __construct()
    {
        $this->middleware('can:puede.ver.pisos')->only('index', 'show');
        $this->middleware('can:puede.crear.pisos')->only('store');
        $this->middleware('can:puede.editar.pisos')->only('update');
        $this->middleware('can:puede.eliminar.pisos')->only('destroy');
    }

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $page = $request['page'];
        $campos = explode(',', $request['campos']);
        $results = [];
        if ($request['campos']) {
            $results = Piso::ignoreRequest(['campos'])->filter()->get($campos);
            return response()->json(compact('results'));
        } else 
        if ($page) {
            $results = Piso::simplePaginate($request['offset']);
            // PisoResource::collection($results);
            // $results->appends(['offset' => $request['offset']]);
        } else {
            $results = Piso::all();
        }
        PisoResource::collection($results);
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
