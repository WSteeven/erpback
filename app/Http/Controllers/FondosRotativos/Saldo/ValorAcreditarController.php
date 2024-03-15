<?php

namespace App\Http\Controllers\FondosRotativos\Saldo;

use App\Http\Controllers\Controller;
use App\Http\Requests\FondosRotativos\Saldo\ValorAcreditarRequest;
use App\Http\Resources\FondosRotativos\Saldo\ValorAcreditarResource;
use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Models\FondosRotativos\Saldo\SaldosFondosRotativos;
use App\Models\FondosRotativos\Saldo\ValorAcreditar;
use App\Models\FondosRotativos\UmbralFondosRotativos;
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
        $results = ValorAcreditar:: where('estado',1)->ignoreRequest(['campos'])->filter()->get();
        $results = ValorAcreditarResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show(Request $request, ValorAcreditar $valor_acreditar)
    {
        $results = $valor_acreditar;
        $results = new ValorAcreditarResource($results->refresh());
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
    public function montoAcreditarUsuario($id)
    {
        $saldo_actual = SaldosFondosRotativos::where('empleado_id', $id)->orderBy('id', 'desc')->first();
        $saldo_actual = $saldo_actual != null ? $saldo_actual->saldo_actual : 0;
        $umbral_usuario = UmbralFondosRotativos::where('empleado_id', $id)->orderBy('id', 'desc')->first();
        $umbral_usuario = $umbral_usuario != null ? $umbral_usuario->valor_minimo : 0;
        $valorRecibir =$umbral_usuario-$saldo_actual;
        $monto_acreditar= abs(ceil($valorRecibir / 10) * 10);
        return response()->json(compact('monto_acreditar'));
    }
}
