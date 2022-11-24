<?php

namespace App\Http\Controllers;

use App\Http\Requests\TipoTransaccionRequest;
use App\Http\Resources\TipoTransaccionResource;
use App\Models\TipoTransaccion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class TipoTransaccionController extends Controller
{
    private $entidad = 'Tipo de transaccion';
    public function __construct()
    {
        $this->middleware('can:puede.ver.tipos_transacciones')->only('index', 'show');
        $this->middleware('can:puede.crear.tipos_transacciones')->only('store');
        $this->middleware('can:puede.editar.tipos_transacciones')->only('update');
        $this->middleware('can:puede.eliminar.tipos_transacciones')->only('destroy');
    }
    /**
     * Listar
     */
    public function index(Request $request)
    {
        $page = $request['page'];
        $tipo = $request['tipo'];
        $results = [];
        if($tipo){
            $datos = TipoTransaccionResource::collection(TipoTransaccion::where('tipo', $tipo)->get());
            $results= TipoTransaccionResource::collection(TipoTransaccion::where('tipo', $tipo)->get());
            if(auth()->user()->hasRole(User::ROL_CONTABILIDAD)){
                $results = TipoTransaccionResource::collection(TipoTransaccion::where('tipo', $tipo)
                    ->where('nombre', '=', 'AJUSTE POR REGULARIZACION')
                    ->get());
                return response()->json(compact('results'));        
            }
            if(auth()->user()->hasRole(User::ROL_BODEGA)){
                $results = TipoTransaccionResource::collection(TipoTransaccion::where('tipo', $tipo)
                    ->where('nombre', '<>', 'LIQUIDACION DE MATERIALES')
                    ->where('nombre', '<>', 'AJUSTE POR REGULARIZACION')
                    ->get());
                return response()->json(compact('results'));        
            }
            if(auth()->user()->hasRole(User::ROL_COORDINADOR)){
                $results = TipoTransaccionResource::collection(TipoTransaccion::where('tipo', $tipo)
                    ->where('nombre', '<>', 'TRANSFERENCIA ENTRE BODEGAS')
                    ->where('nombre', '<>', 'AJUSTE POR REGULARIZACION')
                    ->get());
                return response()->json(compact('results'));
            }
            if(!auth()->user()->hasRole([User::ROL_COORDINADOR, User::ROL_BODEGA, User::ROL_CONTABILIDAD])){
                $results = TipoTransaccionResource::collection(TipoTransaccion::where('tipo', $tipo)
                    ->where('nombre', '<>', 'TRANSFERENCIA ENTRE BODEGAS')
                    ->where('nombre', '<>', 'LIQUIDACION DE MATERIALES')
                    ->where('nombre', '<>', 'AJUSTE POR REGULARIZACION')
                    ->get());
                return response()->json(compact('results'));
            }
        }else if($page){
            $results = TipoTransaccion::simplePaginate($request['offset']);
        }else{
            $results = TipoTransaccion::all();
        }
        TipoTransaccionResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(TipoTransaccionRequest $request)
    {
        //Respuesta
        $modelo = TipoTransaccion::create($request->validated());
        $modelo = new TipoTransaccionResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(TipoTransaccion $tipo_transaccion)
    {
        $modelo = new TipoTransaccionResource($tipo_transaccion);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(TipoTransaccionRequest $request, TipoTransaccion  $tipo_transaccion)
    {
        
        $tipo_transaccion->update($request->all());

        return response()->json(['mensaje' => 'El tipo ha sido actualizado con éxito', 'modelo' => $tipo_transaccion]);
    }

    /**
     * Eliminar
     */
    public function destroy(TipoTransaccion $tipo_transaccion)
    {
        $tipo_transaccion->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
