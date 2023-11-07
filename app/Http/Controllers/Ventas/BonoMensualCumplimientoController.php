<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\BonoMensualCumplimientoRequest;
use App\Http\Resources\Ventas\BonoMensualCumplimientoResource;
use App\Models\Ventas\BonoMensualCumplimiento;
use App\Models\Ventas\Bonos;
use App\Models\Ventas\Vendedor;
use App\Models\Ventas\Ventas;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class BonoMensualCumplimientoController extends Controller
{
    private $entidad = 'BonoMensualCumplimiento';
    public function __construct()
    {
        $this->middleware('can:puede.ver.bono_mensual_cumplimiento')->only('index', 'show');
        $this->middleware('can:puede.crear.bono_mensual_cumplimiento')->only('store');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = BonoMensualCumplimiento::ignoreRequest(['campos'])->filter()->get();
        $results = BonoMensualCumplimientoResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show(Request $request, BonoMensualCumplimiento $bono_mensual_cumplimiento)
    {
        $modelo = new BonoMensualCumplimientoResource($bono_mensual_cumplimiento);
        return response()->json(compact('modelo'));
    }
    public function store(BonoMensualCumplimientoRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $this->tabla_bonos($datos['mes']);
            $modelo = new BonoMensualCumplimiento();
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function update(BonoMensualCumplimientoRequest $request, BonoMensualCumplimiento $bono_mensual_cumplimiento)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $bono_mensual_cumplimiento->update($datos);
            $modelo = new BonoMensualCumplimientoResource($bono_mensual_cumplimiento->refresh());
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, BonoMensualCumplimiento $bono_mensual_cumplimiento)
    {
        $bono_mensual_cumplimiento->delete();
        return response()->json(compact('bono_mensual_cumplimiento'));
    }
    public function tabla_bonos($mes)
    {
        $vendedores = Vendedor::all();
        $bonos_mensuales_cumplimiento  = [];
        foreach ($vendedores as $key => $vendedor) {
            $suma_ventas = $this->calcular_cantidad_ventas($mes, $vendedor->id);
            $bono =  Bonos::where('cant_ventas', '>=', $suma_ventas)->where('cant_ventas', '<=', $suma_ventas)->orderBy('cant_ventas', 'asc')->first();
            $valor = $bono != null ? $bono->valor : 0;
            $bonos_mensuales_cumplimiento[]  = [
                'vendedor_id' => $vendedor->id,
                'cant_ventas' => $suma_ventas,
                'mes' =>  $mes,
                'bono_id' => $bono !=null  ?$bono->id:null,
                'valor' => $valor,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        BonoMensualCumplimiento::insert($bonos_mensuales_cumplimiento);
    }
    public function  calcular_cantidad_ventas($mes, $vendedor_id)
    {
        $fecha = $mes; // Tu fecha en formato "2023-02"
        $parts = explode('-', $fecha); // Divide la fecha por el carácter '-'
        $year = $parts[0]; // Año
        $month = $parts[1]; // Mes
        $cantidad_ventas = Ventas::whereYear('fecha_activ', $year)
            ->whereMonth('fecha_activ',$month)->where('vendedor_id', $vendedor_id)->get()->count();
        return $cantidad_ventas;
    }
}
