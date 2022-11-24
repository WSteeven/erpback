<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubtipoTransaccionRequest;
use App\Http\Resources\SubtipoTransaccionResource;
use App\Models\SubtipoTransaccion;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class SubtipoTransaccionController extends Controller
{
    private $entidad = 'Subtipo de transaccion';
    public function __construct()
    {
        $this->middleware('can:puede.ver.subtipos_transacciones')->only('index', 'show');
        $this->middleware('can:puede.crear.subtipos_transacciones')->only('store');
        $this->middleware('can:puede.editar.subtipos_transacciones')->only('update');
        $this->middleware('can:puede.eliminar.subtipos_transacciones')->only('destroy');
    }

    /**
     * Listar todos los subtipos de transacciones
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = $request['page'];
        $campos = explode(',', $request['campos']);
        $results = [];
        if($request['campos']){
            $results = SubtipoTransaccion::all($campos);
            return response()->json(compact('results'));
        }else
        if ($page) {
            $results = SubtipoTransaccion::simplePaginate($request['offset']);
            // SubtipoTransaccionResource::collection($results);
            // $results->appends(['offset' => $request['offset']]);
        } else {
            $results = SubtipoTransaccion::all();
        }
        $results = SubtipoTransaccionResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar un nuevo subtipo de transaccion
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubtipoTransaccionRequest $request)
    {
        //Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['tipo_transaccion_id'] = $request->safe()->only(['tipo_transaccion'])['tipo_transaccion'];
        
        //Respuesta 
        $modelo = SubtipoTransaccion::create($datos);
        $modelo = new SubtipoTransaccionResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar un recurso especifico.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SubtipoTransaccion $subtipo_transaccion)
    {
        $modelo = new SubtipoTransaccionResource($subtipo_transaccion);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar el recurso especificado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SubtipoTransaccionRequest $request, SubtipoTransaccion $subtipo_transaccion)
    {
        //Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['tipo_transaccion_id'] = $request->safe()->only(['tipo_transaccion'])['tipo_transaccion'];
        
        //Respuesta
        $subtipo_transaccion->update($datos);
        $modelo = new SubtipoTransaccionResource($subtipo_transaccion->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar el recurso especifico.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubtipoTransaccion $subtipo_transaccion)
    {
        $subtipo_transaccion->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');

        return response()->json(compact('mensaje'));
    }
}
