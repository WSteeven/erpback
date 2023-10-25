<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\NominaPrestamos\HorasExtrasSubTipoRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\HorasExtrasSubTipoResource;
use App\Models\RecursosHumanos\NominaPrestamos\HorasExtraTipo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HorasExtrasTipoController extends Controller
{
    private $entidad = 'Horas Extras SubTipo';
    public function __construct()
    {
        $this->middleware('can:puede.ver.horas_extras_tipo')->only('index', 'show');
        $this->middleware('can:puede.crear.horas_extras_tipo')->only('store');
    }

    public function index(Request $request)
    {
        $results = [];
        $results = HorasExtraTipo::ignoreRequest(['campos'])->filter()->get();
        $results = HorasExtrasSubTipoResource::collection($results);
        return response()->json(compact('results'));
    }

    public function prestamos_hipotecario_empleado(Request $request){
       $prestamo= HorasExtraTipo::where('empleado_id',$request->empleado)->where('mes',$request->mes)->sum('valor');
       return response()->json(compact('prestamo'));
    }

    public function store(HorasExtrasSubTipoRequest $request)
    {
        try {
            $datos = $request->validated();

            return;
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['ERROR en el insert de rol de pago', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(HorasExtraTipo $horasExtraTipo)
    {
        $modelo = new HorasExtrasSubTipoResource($horasExtraTipo);
        return response()->json(compact('modelo'), 200);
    }

    public function update(Request $request, $horasExtraTipoId)
    {
        $horasExtraTipo = HorasExtraTipo::find($horasExtraTipoId);
        $horasExtraTipo->nombre = $request->nombre;
        $horasExtraTipo->save();
        return $horasExtraTipo;
    }

    public function destroy($horasExtraTipoId)
    {
        $horasExtraTipo = HorasExtraTipo::find($horasExtraTipoId);
        $horasExtraTipo->delete();
        return $horasExtraTipo;
    }
}
