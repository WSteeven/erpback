<?php

namespace App\Http\Controllers;

use App\Http\Requests\MotivoRequest;
use App\Http\Resources\MotivoResource;
use App\Models\Motivo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class MotivoController extends Controller
{
    private string $entidad = 'Motivo';
    public function __construct()
    {
        $this->middleware('can:puede.ver.motivos')->only('index', 'show');
        $this->middleware('can:puede.crear.motivos')->only('store');
        $this->middleware('can:puede.editar.motivos')->only('update');
        $this->middleware('can:puede.eliminar.motivos')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $page = $request['page'];
        $campos = explode(',', $request['campos']);
        if($request['campos']){
            $results = Motivo::all($campos);
            return response()->json(compact('results'));
        }else
        if ($page) {
            $results = Motivo::simplePaginate($request['offset']);
        } else {
            $results = Motivo::ignoreRequest(['campos'])->filter()->get();
        }
        $results = MotivoResource::collection($results);
        return response()->json(compact('results'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param MotivoRequest $request
     * @return JsonResponse
     */
    public function store(MotivoRequest $request)
    {
        //adaptacion de foreign keys
        $datos = $request->validated();
        $datos['tipo_transaccion_id'] = $request->safe()->only(['tipo_transaccion'])['tipo_transaccion'];

        //Respuesta
        $modelo = Motivo::create($datos);
        $modelo = new MotivoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param Motivo $motivo
     * @return JsonResponse
     */
    public function show(Motivo $motivo)
    {
        $modelo = new MotivoResource($motivo);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MotivoRequest $request
     * @param Motivo $motivo
     * @return JsonResponse
     */
    public function update(MotivoRequest $request, Motivo $motivo)
    {
        //Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['tipo_transaccion_id'] = $request->safe()->only(['tipo_transaccion'])['tipo_transaccion'];

        //Respuesta
        $motivo->update($datos);
        $modelo = new MotivoResource($motivo->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

}
