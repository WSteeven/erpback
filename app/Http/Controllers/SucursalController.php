<?php

namespace App\Http\Controllers;

use App\Http\Requests\SucursalRequest;
use App\Http\Resources\SucursalResource;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Psy\Sudo;
use Src\Shared\Utils;

class SucursalController extends Controller
{
    private $entidad = 'Sucursal';
    public function __construct()
    {
        $this->middleware('can:puede.ver.sucursales')->only('index', 'show');
        $this->middleware('can:puede.crear.sucursales')->only('store');
        $this->middleware('can:puede.editar.sucursales')->only('update');
        $this->middleware('can:puede.eliminar.sucursales')->only('destroy');
    }

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $page = $request['page'];
        $campos = explode(',', $request['campos']);
        $search = $request['search'];
        $results = [];
        if($request['campos']){
            $results = Sucursal::ignoreRequest(['campos'])->filter()->get($campos);
            return response()->json(compact('results'));
        }else if ($page) {
            $results = Sucursal::simplePaginate($request['offset']);
            // SucursalResource::collection($results);
            // $results->appends(['offset' => $request['offset']]);
        } else {
            $results = Sucursal::filter()->get();
            // SucursalResource::collection($results);
        }
        if($search){
            $sucursal = Sucursal::select('id')->where('lugar', 'LIKE', '%'.$search.'%')->first();
 
            if($sucursal) $results = SucursalResource::collection(Sucursal::where('id', $sucursal->id)->get());
        }
        $results = SucursalResource::collection($results);
        return response()->json(compact('results'));
    }


    /**
     * Guardar
     */
    public function store(SucursalRequest $request)
    {   
        //Adaptación de foreign keys
        $datos = $request->validated();
        $datos['administrador_id'] = $request->safe()->only(['administrador'])['administrador'];

        $sucursal = Sucursal::create($datos);
        $modelo = new SucursalResource($sucursal);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(Sucursal $sucursal)
    {
        $modelo = new SucursalResource($sucursal);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(SucursalRequest $request, Sucursal  $sucursal)
    {
        Log::channel('testing')->info('Log', ['Datos recibidos', $request->all()]);
        //Adaptación de foreign keys
        $datos = $request->validated();
        Log::channel('testing')->info('Log', ['Datos validados', $datos]);
        $datos['administrador_id'] = $request->safe()->only(['administrador'])['administrador'];
        
        //Respuesta
        $sucursal->update($datos);
        $modelo = new SucursalResource($sucursal->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(Sucursal $sucursal)
    {
        $sucursal->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
