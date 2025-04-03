<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\HorasExtrasSubTipoResource;
use App\Models\RecursosHumanos\NominaPrestamos\HorasExtraTipo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class HorasExtrasTipoController extends Controller
{
//    private string $entidad = 'Horas Extras SubTipo';
    public function __construct()
    {
        $this->middleware('can:puede.ver.horas_extras_tipo')->only('index', 'show');
        $this->middleware('can:puede.crear.horas_extras_tipo')->only('store');
    }

    public function index()
    {
        $results = HorasExtraTipo::ignoreRequest(['campos'])->filter()->get();
        $results = HorasExtrasSubTipoResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * @throws ValidationException
     */
    public function store(/*HorasExtrasSubTipoRequest $request*/)
    {
        try {
//            $datos = $request->validated();
            throw new Exception(Utils::metodoNoDesarrollado());
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['ERROR en el insert de rol de pago', $e->getMessage(), $e->getLine()]);
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
    }

    public function show(HorasExtraTipo $horasExtraTipo)
    {
        $modelo = new HorasExtrasSubTipoResource($horasExtraTipo);
        return response()->json(compact('modelo'));
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
