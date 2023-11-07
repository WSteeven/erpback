<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\BonoTrimestralCumplimientoRequest;
use App\Http\Resources\Ventas\BonoTrimestralCumplimientoResource;
use App\Models\Ventas\Bonos;
use App\Models\Ventas\BonoTrimestralCumplimiento;
use App\Models\Ventas\Vendedor;
use App\Models\Ventas\Ventas;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;

class BonoTrimestralCumplimientoController extends Controller
{
    private $entidad = 'BonoTrimestralCumplimiento';
    public function __construct()
    {
        $this->middleware('can:puede.ver.bono_trimestral_cumplimiento')->only('index', 'show');
        $this->middleware('can:puede.crear.bono_trimestral_cumplimiento')->only('store');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = BonoTrimestralCumplimiento::ignoreRequest(['campos'])->filter()->get();
        $results = BonoTrimestralCumplimientoResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show(Request $request, BonoTrimestralCumplimiento $bono_trimestral_cumplimiento)
    {
        $modelo = new BonoTrimestralCumplimientoResource($bono_trimestral_cumplimiento);
        return response()->json(compact('modelo'));
    }
    public function store(BonoTrimestralCumplimientoRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $this->tabla_bonos($datos['trimestre']);
            $modelo = new BonoTrimestralCumplimiento();
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function update(BonoTrimestralCumplimientoRequest $request, BonoTrimestralCumplimiento $bono_trimestral_cumplimiento)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $bono_trimestral_cumplimiento->update($datos);
            $modelo = new BonoTrimestralCumplimientoResource($bono_trimestral_cumplimiento->refresh());
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, BonoTrimestralCumplimiento $bono_trimestral_cumplimiento)
    {
        $bono_trimestral_cumplimiento->delete();
        return response()->json(compact('bono_trimestral_cumplimiento'));
    }
    public function tabla_bonos($trimestre)
    {
        $vendedores = Vendedor::all();
        $bonos_mensuales_cumplimiento  = [];
        foreach ($vendedores as $key => $vendedor) {
            $suma_ventas = $this->calcular_cantidad_ventas($trimestre, $vendedor->id);
            $valor = $suma_ventas*5;
            $bonos_mensuales_cumplimiento[]  = [
                'vendedor_id' => $vendedor->id,
                'cant_ventas' => $suma_ventas,
                'trimestre' =>  $trimestre,
                'valor' => $valor,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        BonoTrimestralCumplimiento::insert($bonos_mensuales_cumplimiento);
    }
    public function  calcular_cantidad_ventas($trimestre, $vendedor_id)
    {
        $fecha = $trimestre; // Tu fecha en formato "2023-02"
        $parts = explode('-', $fecha); // Divide la fecha por el carácter '-'
        $year = $parts[1]; // Año
        $quarter =preg_replace('/[A-Za-z]/', '', $parts[0]); // Mes
        $cantidad_ventas = Ventas::whereYear('fecha_activ', $year)
            ->whereRaw('QUARTER(fecha_activ) = ?', [$quarter])
            ->where('vendedor_id', $vendedor_id)
            ->count();
        return $cantidad_ventas;
    }
}
