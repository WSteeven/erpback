<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\BonoMensualCumplimientoRequest;
use App\Http\Resources\Ventas\BonoMensualCumplimientoResource;
use App\Models\Ventas\BonoMensualCumplimiento;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            $bono_mensual_cumplimiento = BonoMensualCumplimiento::create($datos);
            $modelo = new BonoMensualCumplimientoResource($bono_mensual_cumplimiento);
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
}
