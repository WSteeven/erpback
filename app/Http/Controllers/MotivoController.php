<?php

namespace App\Http\Controllers;

use App\Http\Requests\MotivoRequest;
use App\Models\Motivo;
use App\Http\Requests\StoreMotivoRequest;
use App\Http\Requests\UpdateMotivoRequest;
use App\Http\Resources\MotivoResource;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class MotivoController extends Controller
{
    private $entidad = 'Motivo';
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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = $request['page'];
        $campos = explode(',', $request['campos']);
        $results = [];
        if($request['campos']){
            $results = Motivo::all($campos);
            return response()->json(compact('results'));
        }else
        if ($page) {
            $results = Motivo::simplePaginate($request['offset']);
            // SubtipoTransaccionResource::collection($results);
            // $results->appends(['offset' => $request['offset']]);
        } else {
            $results = Motivo::all();
        }
        $results = MotivoResource::collection($results);
        return response()->json(compact('results'));
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMotivoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMotivoRequest $request)
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
     * @param  \App\Models\Motivo  $motivo
     * @return \Illuminate\Http\Response
     */
    public function show(Motivo $motivo)
    {
        $modelo = new MotivoResource($motivo);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMotivoRequest  $request
     * @param  \App\Models\Motivo  $motivo
     * @return \Illuminate\Http\Response
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Motivo  $motivo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Motivo $motivo)
    {
        $motivo->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');

        return response()->json(compact('mensaje'));
    }
}
