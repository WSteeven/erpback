<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\TipoChargebackRequest;
use App\Http\Resources\Ventas\TipoChargebackResource;
use App\Models\Ventas\TipoChargeback;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;

class TipoChargebackController extends Controller
{
    private $entidad = 'TipoChargeBack';
    public function __construct()
    {
        $this->middleware('can:puede.ver.tipos_chargebacks')->only('index', 'show');
        $this->middleware('can:puede.crear.tipos_chargebacks')->only('store');
        $this->middleware('can:puede.editar.tipos_chargebacks')->only('update');
        $this->middleware('can:puede.eliminar.tipos_chargebacks')->only('destroy');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = TipoChargeback::ignoreRequest(['campos'])->filter()->get();
        $results = TipoChargebackResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show(Request $request, TipoChargeback $chargebacks)
    {
        $results = new TipoChargebackResource($chargebacks);

        return response()->json(compact('results'));
    }
    public function store(TipoChargebackRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $chargebacks = TipoChargeback::create($datos);
            $modelo = new TipoChargebackResource($chargebacks);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function update(TipoChargebackRequest $request, TipoChargeback $chargebacks)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $chargebacks->update($datos);
            $modelo = new TipoChargebackResource($chargebacks->refresh());
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, TipoChargeback $chargebacks)
    {
        $chargebacks->delete();
        return response()->json(compact('chargebacks'));
    }
}
