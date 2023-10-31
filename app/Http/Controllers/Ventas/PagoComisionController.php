<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\PagoComisionRequest;
use App\Http\Resources\Ventas\PagoComisionResource;
use App\Models\Ventas\Chargebacks;
use App\Models\Ventas\PagoComision;
use App\Models\Ventas\Vendedor;
use App\Models\Ventas\Ventas;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class PagoComisionController extends Controller
{
    private $entidad = 'PagoComision';
    public function __construct()
    {
        $this->middleware('can:puede.ver.pago_comision')->only('index', 'show');
        $this->middleware('can:puede.crear.pago_comision')->only('store');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = PagoComision::ignoreRequest(['campos'])->filter()->get();
        $results = PagoComisionResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show(Request $request, PagoComision $pago_comision)
    {
        $modelo = new PagoComisionResource($pago_comision);

        return response()->json(compact('modelo'));
    }
    public function store(PagoComisionRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $this->tabla_comisiones($datos['fecha']);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            $modelo = new PagoComision();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    private function tabla_comisiones($fecha)
    {
        $vendedor = Vendedor::get();
        $pagos_comision  = [];
        foreach ($vendedor as $vendedor) {
            $chargeback = Chargebacks::where('fecha', '<=', $fecha)->sum('valor');
            $comisiones = Ventas::where('fecha_activ', '<=', $fecha)->where('vendedor_id', $vendedor->id)->orderBy('id')->skip(5)->take(999999)->get();
            $comisiones = array_reduce($comisiones->toArray(), function ($carry, $item) {
                return $carry + $item["comision_vendedor"];
            }, 0);
            $pagos_comision[]  = [
                'fecha' => $fecha,
                'chargeback' => $chargeback,
                'vendedor_id' => $vendedor->id,
                'valor' =>  $comisiones
            ];
        }
        Log::channel('testing')->info('Log', [compact('pagos_comision')]);

        // PagoComision::insert($pagos_comision);

    }

    public function update(PagoComisionRequest $request, PagoComision $pago_comision)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $pago_comision->update($datos);
            $modelo = new PagoComisionResource($pago_comision->refresh());
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, PagoComision $pago_comision)
    {
        $pago_comision->delete();
        return response()->json(compact('pago_comision'));
    }
}
