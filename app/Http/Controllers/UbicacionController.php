<?php

namespace App\Http\Controllers;

use App\Http\Requests\UbicacionRequest;
use App\Http\Resources\UbicacionResource;
use App\Models\Percha;
use App\Models\Piso;
use App\Models\Ubicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class UbicacionController extends Controller
{
    private $entidad = 'Ubicacion';
    public function __construct()
    {
        $this->middleware('can:puede.ver.ubicaciones')->only('index', 'show');
        $this->middleware('can:puede.crear.ubicaciones')->only('store');
        $this->middleware('can:puede.editar.ubicaciones')->only('update');
        $this->middleware('can:puede.eliminar.ubicaciones')->only('destroy');
    }

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $page = $request['page'];
        $search = $request['search'];
        $sucursal=$request['sucursal'];
        $results = [];
        if ($search) {
            $ubicacion = Ubicacion::select('id')->where('codigo', 'LIKE', '%' . $search . '%')->first();

            if ($ubicacion) $results = UbicacionResource::collection(Ubicacion::where('id', $ubicacion->id)->get());
        } else if($page){
            $results = Ubicacion::simplePaginate($request['offset']);
        } else{
            $results = UbicacionResource::collection(Ubicacion::all());
        }
        if($sucursal){
            Log::channel('testing')->info('Log', ['Sucursal recibida:', $request['sucursal']]);
            $results = Ubicacion::select(["ubicaciones.id", "codigo", "percha_id", "piso_id",])
                ->join('perchas', 'percha_id', '=', 'perchas.id')
                ->where('perchas.sucursal_id', $sucursal)->get();
        
        }
        UbicacionResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(UbicacionRequest $request)
    {
        //Adaptacion de foreign keys
        $datos = $request->validated();
        if($request->percha && $request->piso){
            $datos['percha_id'] = $request->safe()->only(['percha'])['percha'];
            $datos['piso_id'] = $request->safe()->only(['piso'])['piso'];
        }else{
            $datos['percha_id'] = $request->safe()->only(['percha'])['percha'];
            //Log::channel('testing')->info('Log', ['Datos en el else:', $datos]);
        }

        $ubicacion = Ubicacion::create($datos);
        $modelo = new UbicacionResource($ubicacion);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Consultar
     */
    public function show(Ubicacion $ubicacion)
    {
        $modelo = new UbicacionResource($ubicacion);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(UbicacionRequest $request, Ubicacion  $ubicacion)
    {
        //Respuesta
        $ubicacion->update($request->validated());
        $modelo = new UbicacionResource($ubicacion->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }


    public function destroy(Ubicacion $ubicacion)
    {
        $ubicacion->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
