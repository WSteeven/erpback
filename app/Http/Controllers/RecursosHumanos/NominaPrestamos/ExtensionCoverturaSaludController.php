<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExtensionCoverturaSaludRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\ExtensionCoverturaSaludResource;
use App\Models\RecursosHumanos\NominaPrestamos\ExtensionCoverturaSalud ;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExtensionCoverturaSaludController extends Controller
{
    private $entidad = 'Extension Covertura de Salud';
    public function __construct()
    {
        $this->middleware('can:puede.ver.covertura_salud')->only('index', 'show');
        $this->middleware('can:puede.crear.covertura_salud')->only('store');
    }

    public function index(Request $request)
    {
        $results = [];
        $results = ExtensionCoverturaSalud::ignoreRequest(['campos'])->filter()->get();
        $results = ExtensionCoverturaSaludResource::collection($results);
        return response()->json(compact('results'));
    }

    public function extension_covertura_salud_empleado(Request $request){
       $valor= ExtensionCoverturaSalud::where('empleado_id',$request->empleado)->where('mes',$request->mes)->sum('aporte');
       return response()->json(compact('valor'));
    }

    public function store(ExtensionCoverturaSaludRequest $request)
    {
        try {
            $datos = $request->validated();

            return;
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['ERROR en el insert de rol de pago', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(ExtensionCoverturaSalud $extensionCoverturaSalud)
    {
        $modelo = new ExtensionCoverturaSaludResource($extensionCoverturaSalud);
        return response()->json(compact('modelo'), 200);
    }

    public function update(Request $request, $extensionCoverturaSaludId)
    {
        $extensionCoverturaSalud = ExtensionCoverturaSalud::find($extensionCoverturaSaludId);
        $extensionCoverturaSalud->nombre = $request->nombre;
        $extensionCoverturaSalud->save();
        return $extensionCoverturaSalud;
    }

    public function destroy($extensionCoverturaSaludId)
    {
        $extensionCoverturaSalud = ExtensionCoverturaSalud::find($extensionCoverturaSaludId);
        $extensionCoverturaSalud->delete();
        return $extensionCoverturaSalud;
    }
}
