<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\ChargebacksRequest;
use App\Http\Resources\Ventas\ChargebacksResource;
use App\Models\Ventas\Chargebacks;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class ChargebacksController extends Controller
{
    private $entidad = 'ChargeBack';
    public function __construct()
    {
        $this->middleware('can:puede.ver.chargebacks')->only('index', 'show');
        $this->middleware('can:puede.crear.chargebacks')->only('store');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = Chargebacks::ignoreRequest(['campos'])->filter()->get();
        $results = ChargebacksResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show(Request $request, ChargeBacks $chargeback)
    {
        $modelo = new ChargeBacksResource($chargeback);
        return response()->json(compact('modelo'));
    }
    public function store(ChargebacksRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $chargebacks = ChargeBacks::create($datos);
            $modelo = new ChargeBacksResource($chargebacks);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function update(ChargeBacksRequest $request, ChargeBacks $chargebacks)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $chargebacks->update($datos);
            $modelo = new ChargeBacksResource($chargebacks->refresh());
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, ChargeBacks $chargebacks)
    {
        $chargebacks->delete();
        return response()->json(compact('chargebacks'));
    }
}
