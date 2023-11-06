<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\BonoTrimestralCumplimientoRequest;
use App\Http\Resources\Ventas\BonoTrimestralCumplimientoResource;
use App\Models\Ventas\BonoTrimestralCumplimiento;
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
            $bono_trimestral_cumplimiento = BonoTrimestralCumplimiento::create($datos);
            $modelo = new BonoTrimestralCumplimientoResource($bono_trimestral_cumplimiento);
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
}
