<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\EscenarioVentaJPRequest;
use App\Http\Resources\Ventas\EscenarioVentaJPResource;
use App\Models\Ventas\EscenarioVentaJP;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;

class EscenarioVentaJPController extends Controller
{
    private $entidad = 'Esenario Venta';
    public function __construct()
    {
        $this->middleware('can:puede.ver.escenarios_ventas_jp')->only('index', 'show');
        $this->middleware('can:puede.crear.escenarios_ventas_jp')->only('store');
        $this->middleware('can:puede.editar.escenarios_ventas_jp')->only('update');
        $this->middleware('can:puede.eliminar.escenarios_ventas_jp')->only('destroy');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = EscenarioVentaJP::ignoreRequest(['campos'])->filter()->get();
        $results = EscenarioVentaJPResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show(Request $request, EscenarioVentaJP $escenario_venta_jp)
    {
        $modelo = new EscenarioVentaJPResource($escenario_venta_jp);

        return response()->json(compact('modelo'));
    }
    public function store(EscenarioVentaJPRequest $request)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $escenario_venta_jp = EscenarioVentaJP::create($datos);
            $modelo = new EscenarioVentaJPResource($escenario_venta_jp);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function update(EscenarioVentaJPRequest $request, EscenarioVentaJP $escenario_venta_jp)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $escenario_venta_jp->update($datos);
            $modelo = new EscenarioVentaJPResource($escenario_venta_jp->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, EscenarioVentaJP $escenario_venta_jp)
    {
        $escenario_venta_jp->delete();
        return response()->json(compact('comision'));
    }

}
