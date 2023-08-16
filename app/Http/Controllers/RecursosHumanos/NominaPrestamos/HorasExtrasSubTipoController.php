<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\HorasExtrasSubTipoRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\HorasExtrasSubTipoResource;
use App\Models\RecursosHumanos\NominaPrestamos\HorasExtraSubTipo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HorasExtrasSubTipoController extends Controller
{
    private $entidad = 'Horas Extras SubTipo';
    public function __construct()
    {
        $this->middleware('can:puede.ver.horas_extras_subtipo')->only('index', 'show');
        $this->middleware('can:puede.crear.horas_extras_subtipo')->only('store');
    }

    public function index(Request $request)
    {
        $results = [];
        $results = HorasExtraSubTipo::ignoreRequest(['campos'])->filter()->get();
        $results = HorasExtrasSubTipoResource::collection($results);
        return response()->json(compact('results'));
    }

    public function prestamos_hipotecario_empleado(Request $request){
       $prestamo= HorasExtraSubTipo::where('empleado_id',$request->empleado)->where('mes',$request->mes)->sum('valor');
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

    public function show(HorasExtraSubTipo $horasExtraSubTipo)
    {
        $modelo = new HorasExtrasSubTipoResource($horasExtraSubTipo);
        return response()->json(compact('modelo'), 200);
    }

    public function update(Request $request, $horasExtraSubTipoId)
    {
        $horasExtraSubTipo = HorasExtraSubTipo::find($horasExtraSubTipoId);
        $horasExtraSubTipo->nombre = $request->nombre;
        $horasExtraSubTipo->save();
        return $horasExtraSubTipo;
    }

    public function destroy($horasExtraSubTipoId)
    {
        $horasExtraSubTipo = HorasExtraSubTipo::find($horasExtraSubTipoId);
        $horasExtraSubTipo->delete();
        return $horasExtraSubTipo;
    }
}
