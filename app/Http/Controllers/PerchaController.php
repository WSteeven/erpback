<?php

namespace App\Http\Controllers;

use App\Http\Requests\PerchaRequest;
use App\Http\Resources\PerchaResource;
use App\Models\Percha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Src\Shared\Utils;

class PerchaController extends Controller
{
    private $entidad = 'Percha';
    public function __construct()
    {
        $this->middleware('can:puede.ver.perchas')->only('index', 'show');
        $this->middleware('can:puede.crear.perchas')->only('store');
        $this->middleware('can:puede.editar.perchas')->only('update');
        $this->middleware('can:puede.eliminar.perchas')->only('destroy');

    }

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $page = $request['page'];
        $results = [];
        
        if ($page) {
            $results = Percha::simplePaginate($request['offset']);
            PerchaResource::collection($results);
            $results->appends(['offset' => $request['offset']]);
        } else {
            $results = Percha::all();
            PerchaResource::collection($results);
        }
        return response()->json(compact('results'));
}


    /**
     * Guardar
     */
    public function store(PerchaRequest $request)
    {
        Log::channel('testing')->info('Log', ['request_recibida', $request->all()]);
        //Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['sucursal_id']= $request->safe()->only(['sucursal'])['sucursal'];

        //Respuesta
        $modelo = Percha::create($datos);
        $modelo = new PerchaResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Consultar
     */
    public function show(Percha $percha)
    {
        $modelo = new PerchaResource($percha);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(PerchaRequest $request, Percha  $percha)
    {
        //Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['sucursal_id']= $request->safe()->only(['sucursal'])['sucursal'];

        //Respuesta
        $percha->update($datos);
        $modelo = new PerchaResource($percha->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(Percha $percha)
    {
        $percha->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
