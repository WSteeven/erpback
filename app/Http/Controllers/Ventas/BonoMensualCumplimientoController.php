<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\BonoMensualCumplimientoRequest;
use App\Http\Resources\Ventas\BonoMensualCumplimientoResource;
use App\Models\Ventas\BonoMensualCumplimiento;
use App\Models\Ventas\BonoPorcentual;
use App\Models\Ventas\Bono;
use App\Models\Ventas\UmbralVenta;
use App\Models\Ventas\Vendedor;
use App\Models\Ventas\Venta;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class BonoMensualCumplimientoController extends Controller
{
    private $entidad = 'Bono Mensual de Cumplimiento';
    public function __construct()
    {
        $this->middleware('can:puede.ver.bonos_mensuales_cumplimientos')->only('index', 'show');
        $this->middleware('can:puede.crear.bonos_mensuales_cumplimientos')->only('store');
        $this->middleware('can:puede.editar.bonos_mensuales_cumplimientos')->only('update');
        $this->middleware('can:puede.eliminar.bonos_mensuales_cumplimientos')->only('destroy');
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
            DB::beginTransaction();
            $datos = $request->validated();
            $this->tabla_bonos($datos['mes']);
            $modelo = [];
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function update(BonoMensualCumplimientoRequest $request, BonoMensualCumplimiento $bono_mensual_cumplimiento)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $bono_mensual_cumplimiento->update($datos);
            $modelo = new BonoMensualCumplimientoResource($bono_mensual_cumplimiento->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
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
        try {
            DB::beginTransaction();
            $vendedores = Vendedor::all();
            foreach ($vendedores as $key => $vendedor) {
                $suma_ventas = $this->calcular_cantidad_ventas($mes, $vendedor->empleado_id);
                $bono = $this->calcular_bono($suma_ventas, $vendedor);
                $valor_bono =  $bono != null  ? $bono : null;
                if ($vendedor->tipo_vendedor === "VENDEDOR") {
                    $valor_bono = $valor_bono !== null ?  $valor_bono->valor : 0;
                } else {
                    $valor_bono = $valor_bono !== null ?  $valor_bono->comision : 0;
                }
                $bono_mensual = BonoMensualCumplimiento::create([
                    'vendedor_id' => $vendedor->empleado_id,
                    'cant_ventas' => $suma_ventas,
                    'mes' =>  $mes,
                    'bono_id' => $bono != null  ? $bono->id : null,
                    'valor' =>  $valor_bono,
                ]);
                Log::channel('testing')->info('Log', ['bono_mensual', $bono_mensual]);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::channel('testing')->info('Log', ['Ha ocurrido un error al calcular bonos', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al calcular bonos' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function calcular_bono($suma_ventas, $vendedor)
    {
        try {
            DB::beginTransaction();
            $valor = null;
            if ($vendedor->tipo_vendedor == "VENDEDOR") {
                $bono =  Bono::where('cant_ventas', '<=', $suma_ventas)
                    ->first();
                $valor = $bono;
            } else {
                $meta_ventas = UmbralVenta::where('vendedor_id', $vendedor->id)->first();
                $cantidad_ventas = $meta_ventas == !null ? $meta_ventas->cantidad_ventas : 1;
                $porcentaje =  $suma_ventas / $cantidad_ventas;
                $bono_porcentual = BonoPorcentual::where('porcentaje', '<=', ($porcentaje * 100))->where('porcentaje', '>', 0)->orderBy('id', 'desc')->first();
                $valor = $bono_porcentual;
            }
            DB::commit();
            return $valor;
        } catch (Exception $e) {
            DB::rollback();
            Log::channel('testing')->info('Log', ['Ha ocurrido un error al calcular bonos', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al calcular bonos' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function  calcular_cantidad_ventas($mes, $vendedor_id)
    {
        try {
            DB::beginTransaction();

            $vendedor = Vendedor::find($vendedor_id);
            $fecha = $mes; // Tu fecha en formato "2023-02"
            $parts = explode('-', $fecha); // Divide la fecha por el carácter '-'
            $year = $parts[0]; // Año
            $month = $parts[1]; // Mes
            if ($vendedor->tipo_vendedor == 'VENDEDOR') {
                $cantidad_ventas = Venta::whereYear('fecha_activacion', $year)
                    ->whereMonth('fecha_activacion', $month)
                    ->where('estado_activacion', 'APROBADO')
                    ->where('vendedor_id', $vendedor_id)
                    ->get()
                    ->count();
            } else {
                $cantidad_ventas = Venta::join('ventas_vendedores', 'ventas_ventas.vendedor_id', '=', 'ventas_vendedores.empleado_id')
                    ->whereYear('fecha_activacion', $year)
                    ->whereMonth('fecha_activacion', $month)
                    ->where('estado_activacion', 'APROBADO')
                    ->where('ventas_vendedores.jefe_inmediato_id', $vendedor->empleado_id)
                    ->get()
                    ->count();
            }
            DB::commit();
            Log::channel('testing')->info('Log', ['Cantidad de ventas', $cantidad_ventas]);
            return $cantidad_ventas;
        } catch (Exception $e) {
            DB::rollback();
            Log::channel('testing')->info('Log', ['Ha ocurrido un error al calcular cantidad de ventas', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al calcular cantidad de ventas' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
