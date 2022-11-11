<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivoFijoRequest;
use App\Http\Resources\ActivoFijoResource;
use App\Models\ActivoFijo;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class ActivoFijoController extends Controller
{
    private $entidad = 'Activo Fijo';
    public function __construct()
    {
        $this->middleware('can:puede.ver.activos_fijos')->only('index', 'show');
        $this->middleware('can:puede.crear.activos_fijos')->only('store');
        $this->middleware('can:puede.editar.activos_fijos')->only('update');
        $this->middleware('can:puede.eliminar.activos_fijos')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = $request['page'];
        $results = [];
        
        if ($page) {
            $results = ActivoFijo::simplePaginate($request['offset']);
            ActivoFijoResource::collection($results);
            $results->appends(['offset' => $request['offset']]);
        } else {
            $results = ActivoFijo::filter()->get();
            ActivoFijoResource::collection($results);
        }
        return response()->json(compact('results'));
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ActivoFijoRequest $request)
    {
        //Adaptacion de foreign keys
        $datos = $request->validated();
        // $datos['detalle_id'] = $request->safe()->only(['detalle'])['detalle'];
        $datos['empleado_id'] = $request->safe()->only(['empleado'])['empleado'];
        $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
        $datos['condicion_id'] = $request->safe()->only(['condicion'])['condicion'];

        //Respuesta
        $modelo = ActivoFijo::create($datos);
        $modelo = new ActivoFijoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ActivoFijo  $activoFijo
     * @return \Illuminate\Http\Response
     */
    public function show(ActivoFijo $activo)
    {
        $modelo = new ActivoFijoResource($activo);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ActivoFijo  $activoFijo
     * @return \Illuminate\Http\Response
     */
    public function update(ActivoFijoRequest $request, ActivoFijo $activo)
    {
        //Adaptacion de foreign keys
        $datos = $request->validated();
        // $datos['detalle_id'] = $request->safe()->only(['detalle'])['detalle'];
        $datos['empleado_id'] = $request->safe()->only(['empleado'])['empleado'];
        $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
        $datos['condicion_id'] = $request->safe()->only(['condicion'])['condicion'];

        //Respuesta
        $activo->update($datos);
        $modelo = new ActivoFijoResource($activo->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        
        return response()->json(compact('modelo', 'mensaje'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ActivoFijo  $activoFijo
     * @return \Illuminate\Http\Response
     */
    public function destroy(ActivoFijo $activo)
    {
        $activo->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');

        return response()->json(compact('mensaje'));
    }
}
