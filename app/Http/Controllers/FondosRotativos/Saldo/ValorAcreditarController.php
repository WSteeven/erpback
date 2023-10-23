<?php

namespace App\Http\Controllers\FondosRotativos\Saldo;

use App\Http\Controllers\Controller;
use App\Http\Requests\FondosRotativos\Saldo\ValorAcreditarRequest;
use App\Http\Resources\FondosRotativos\Saldo\ValorAcreditarResource;
use App\Models\FondosRotativos\Saldo\ValorAcreditar;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class ValorAcreditarController extends Controller
{
    private $entidad = 'Valor Acreditar';
    public function __construct()
    {
        $this->middleware('can:puede.ver.valor_acreditar')->only('index', 'show');
        $this->middleware('can:puede.crear.valor_acreditar')->only('store');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = ValorAcreditar::ignoreRequest(['campos'])->filter()->get();
        $results = ValorAcreditarResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show(Request $request, ValorAcreditar $valor_acreditar)
    {
        $results = $valor_acreditar;
        return response()->json(compact('results'));
    }
    public function store(ValorAcreditarRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $valoracreditar = ValorAcreditar::create($datos);
            $modelo = new ValorAcreditarResource($valoracreditar);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function update(ValorAcreditarRequest $request, ValorAcreditar $valoracreditar)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $valoracreditar = ValorAcreditar::findOrFail($request->id);
            $valoracreditar->update($datos);
            $modelo = new ValorAcreditarResource($valoracreditar->refresh());
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()]);
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, ValorAcreditar $valoracreditar)
    {
        $valoracreditar->delete();
        return response()->json(compact('valoracreditar'));
    }
}
