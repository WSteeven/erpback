<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\ComisionRequest;
use App\Http\Resources\Ventas\ComisionResource;
use App\Models\Ventas\Comision;
use App\Models\Ventas\ProductoVenta;
use App\Models\Ventas\Vendedor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;

class ComisionController extends Controller
{
    private $entidad = 'Comision';
    public function __construct()
    {
        $this->middleware('can:puede.ver.comisiones')->only('index', 'show');
        $this->middleware('can:puede.crear.comisiones')->only('store');
        $this->middleware('can:puede.editar.comisiones')->only('update');
        $this->middleware('can:puede.eliminar.comisiones')->only('destroy');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = Comision::ignoreRequest(['campos'])->filter()->get();
        $results = ComisionResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show(Request $request, Comision $comision)
    {
        $modelo = new ComisionResource($comision);

        return response()->json(compact('modelo'));
    }
    public function store(ComisionRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $comision = Comision::create($datos);
            $modelo = new ComisionResource($comision);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function update(ComisionRequest $request, Comision $comision)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $comision->update($datos);
            $modelo = new ComisionResource($comision->refresh());
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, Comision $comision)
    {
        $comision->delete();
        return response()->json(compact('comision'));
    }
    public function obtener_comision($idProducto,$forma_pago,$vendedor){
        $vendedor = Vendedor::where('empleado_id',$vendedor)->first();
        $tipo_vendedor = $vendedor->tipo_vendedor;
        $producto = ProductoVenta::where('id', $idProducto)->first();
        $comision = Comision::where('plan_id', $producto->plan_id)->where('forma_pago', $forma_pago)->where('tipo_vendedor',$tipo_vendedor)->first();
        $comision_valor = floatval($comision != null ? $comision->comision:0);
        $comision_value = $tipo_vendedor== 'VENDEDOR'?  ($producto->precio*$comision_valor)/100:$comision_valor ;
        return response()->json(compact('comision_value'));
    }
}
