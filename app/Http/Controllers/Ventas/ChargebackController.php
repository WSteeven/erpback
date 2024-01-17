<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\ChargebackRequest;
use App\Http\Resources\Ventas\ChargebackResource;
use App\Models\Ventas\Chargeback;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class ChargebackController extends Controller
{
    private $entidad = 'ChargeBack';
    public function __construct()
    {
        $this->middleware('can:puede.ver.chargebacks')->only('index', 'show');
        $this->middleware('can:puede.crear.chargebacks')->only('store');
        $this->middleware('can:puede.editar.chargebacks')->only('update');
        $this->middleware('can:puede.eliminar.chargebacks')->only('destroy');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = Chargeback::ignoreRequest(['campos'])->filter()->get();
        $results = ChargebackResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show(Request $request, Chargeback $chargeback)
    {
        $modelo = new ChargebackResource($chargeback);
        return response()->json(compact('modelo'));
    }
    public function store(ChargebackRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $Chargeback = Chargeback::create($datos);
            $modelo = new ChargebackResource($Chargeback);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function update(ChargebackRequest $request, Chargeback $Chargeback)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $Chargeback->update($datos);
            $modelo = new ChargebackResource($Chargeback->refresh());
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, Chargeback $Chargeback)
    {
        $Chargeback->delete();
        return response()->json(compact('Chargeback'));
    }
}
