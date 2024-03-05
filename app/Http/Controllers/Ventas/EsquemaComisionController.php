<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\EsquemaComisionRequest;
use App\Http\Resources\Ventas\EsquemaComisionResource;
use App\Models\Ventas\EsquemaComision;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;

class EsquemaComisionController extends Controller
{
    private $entidad = 'Esquema Comision';
    public function __construct()
    {
        $this->middleware('can:puede.ver.esquemas_comisiones')->only('index', 'show');
        $this->middleware('can:puede.crear.esquemas_comisiones')->only('store');
        $this->middleware('can:puede.editar.esquemas_comisiones')->only('update');
        $this->middleware('can:puede.eliminar.esquemas_comisiones')->only('destroy');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = EsquemaComision::ignoreRequest(['campos'])->filter()->get();
        $results = EsquemaComisionResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show(Request $request, EsquemaComision $esquema_comision)
    {
        $modelo = new EsquemaComisionResource($esquema_comision);

        return response()->json(compact('modelo'));
    }
    public function store(EsquemaComisionRequest $request)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $esquema_comision = EsquemaComision::create($datos);
            $modelo = new EsquemaComisionResource($esquema_comision);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function update(EsquemaComisionRequest $request, EsquemaComision $esquema_comision)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $esquema_comision->update($datos);
            $modelo = new EsquemaComisionResource($esquema_comision->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, EsquemaComision $esquema_comision)
    {
        $esquema_comision->delete();
        return response()->json(compact('comision'));
    }
    public static function obtener_esquema_comision($id){
        $esquema_comision = EsquemaComision::where('id',$id)->first();
        return $esquema_comision;
    }
}
