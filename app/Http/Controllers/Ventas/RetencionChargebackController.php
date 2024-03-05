<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Resources\Ventas\RetencionChargebackResource;
use App\Models\Ventas\RetencionChargeback;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class RetencionChargebackController extends Controller
{
    private $entidad = 'Retención de Chargeback';
    private $servicio;
    public function __construct()
    {
        // $this->servicio = new PagoComisionService();
        $this->middleware('can:puede.ver.retenciones_chargebacks')->only('index', 'show');
        $this->middleware('can:puede.crear.retenciones_chargebacks')->only('store');
        $this->middleware('can:puede.editar.retenciones_chargebacks')->only('update');
        $this->middleware('can:puede.eliminar.retenciones_chargebacks')->only('destroy');
    }

    public function index(Request $request)
    {
        $results = RetencionChargeback::ignoreRequest(['campos'])->filter()->get();
        $results = RetencionChargebackResource::collection($results);
        return response()->json(compact('results'));
    }

    public function show(Request $request, RetencionChargeback $retencion)
    {
        $modelo = new RetencionChargebackResource($retencion);

        return response()->json(compact('modelo'));
    }
    public function marcarPagada(RetencionChargeback $retencion)
    {
        try {
            if (!$retencion->pagado) {
                $retencion->pagada = !$retencion->pagada;
                $retencion->save();
                $modelo = new RetencionChargebackResource($retencion->refresh());
                $mensaje = Utils::obtenerMensaje($this->entidad, 'update'); // 'El corte de pago de comisión ha sido actualizado con éxito';
                return response()->json(compact('modelo', 'mensaje'));
            } else {
                throw new Exception('Esta retención ya ha sido marcada como pagada previamente');
            }
        } catch (\Throwable $e) {
            throw ValidationException::withMessages(['error' => $e->getMessage()]);
        }
    }
}
