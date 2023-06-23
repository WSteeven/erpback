<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\PrestamoHipotecarioRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\PrestamoHipotecarioResource;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoHipotecario;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PrestamoHipotecarioController extends Controller
{
    private $entidad = 'Prestamo Hipotecario';
    public function __construct()
    {
        $this->middleware('can:puede.ver.prestamo_hipotecario')->only('index', 'show');
        $this->middleware('can:puede.crear.prestamo_hipotecario')->only('store');
    }

    public function index(Request $request)
    {
        $results = [];
        $results = PrestamoHipotecario::ignoreRequest(['campos'])->filter()->get();
        $results = PrestamoHipotecarioResource::collection($results);
        return response()->json(compact('results'));
    }

    public function prestamos_hipotecario_empleado(Request $request){
       $prestamo= PrestamoHipotecario::where('empleado_id',$request->empleado)->where('mes',$request->mes)->sum('valor');
       return response()->json(compact('prestamo'));
    }

    public function store(PrestamoHipotecarioRequest $request)
    {
        try {
            $datos = $request->validated();

            return;
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['ERROR en el insert de rol de pago', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(PrestamoHipotecario $prestamoHipotecario)
    {
        $modelo = new PrestamoHipotecarioResource($prestamoHipotecario);
        return response()->json(compact('modelo'), 200);
    }

    public function update(Request $request, $prestamoHipotecarioId)
    {
        $prestamoHipotecario = PrestamoHipotecario::find($prestamoHipotecarioId);
        $prestamoHipotecario->nombre = $request->nombre;
        $prestamoHipotecario->save();
        return $prestamoHipotecario;
    }

    public function destroy($prestamoHipotecarioId)
    {
        $prestamoHipotecario = PrestamoHipotecario::find($prestamoHipotecarioId);
        $prestamoHipotecario->delete();
        return $prestamoHipotecario;
    }
}
