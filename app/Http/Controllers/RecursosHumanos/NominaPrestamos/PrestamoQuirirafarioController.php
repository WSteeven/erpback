<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\PrestamoQuirorafarioRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\PrestamoQuirorafarioResource;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoQuirorafario;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PrestamoQuirirafarioController extends Controller
{
    private $entidad = 'Prestamo Quirorafario';
    public function __construct()
    {
        $this->middleware('can:puede.ver.prestamo_quirorafario')->only('index', 'show');
        $this->middleware('can:puede.crear.prestamo_quirorafario')->only('store');
    }

    public function index(Request $request)
    {
        $results = [];
        $results = PrestamoQuirorafario::ignoreRequest(['campos'])->filter()->get();
        $results = PrestamoQuirorafarioResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(PrestamoQuirorafarioRequest $request)
    {
        try {
            $datos = $request->validated();

            return;
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['ERROR en el insert de rol de pago', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(PrestamoQuirorafario $prestamoQuirorafario)
    {
        $modelo = new PrestamoQuirorafarioResource($prestamoQuirorafario);
        return response()->json(compact('modelo'), 200);
    }
    public function prestamos_quirorafario_empleado(Request $request){
        $prestamo= PrestamoQuirorafario::where('empleado_id',$request->empleado)->where('mes',$request->mes)->sum('valor');
        return response()->json(compact('prestamo'));
     }


    public function update(Request $request, $prestamoQuirorafarioId)
    {
        $prestamoQuirorafario = PrestamoQuirorafario::find($prestamoQuirorafarioId);
        $prestamoQuirorafario->nombre = $request->nombre;
        $prestamoQuirorafario->save();
        return $prestamoQuirorafario;
    }

    public function destroy($prestamoQuirorafarioId)
    {
        $prestamoQuirorafario = PrestamoQuirorafario::find($prestamoQuirorafarioId);
        $prestamoQuirorafario->delete();
        return $prestamoQuirorafario;
    }
}
