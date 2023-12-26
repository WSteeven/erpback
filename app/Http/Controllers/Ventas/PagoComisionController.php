<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\PagoComisionRequest;
use App\Http\Resources\Ventas\PagoComisionResource;
use App\Models\Producto;
use App\Models\Ventas\Chargebacks;
use App\Models\Ventas\Comisiones;
use App\Models\Ventas\Modalidad;
use App\Models\Ventas\PagoComision;
use App\Models\Ventas\ProductoVentas;
use App\Models\Ventas\Vendedor;
use App\Models\Ventas\Ventas;
use Carbon\Carbon;
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

            $this->tabla_comisiones($datos['fecha_inicio'], $datos['fecha_fin']);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            $modelo = new PagoComision();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    private function tabla_comisiones($fecha_inicio, $fecha_fin)
    {
        try {
            DB::beginTransaction();
            $vendedor = Vendedor::get();
            foreach ($vendedor as $vendedor) {
                $chargeback = Chargebacks::select(DB::raw('SUM(valor) AS total_chargeback'))
                    ->join('ventas_ventas', 'ventas_chargebacks.venta_id', '=', 'ventas_ventas.id')
                    ->where('ventas_ventas.vendedor_id', $vendedor->id)
                    ->whereBetween('ventas_chargebacks.fecha', [$fecha_inicio, $fecha_fin])
                    ->get();
                $comisiones = $this->calcular_comisiones($vendedor->empleado_id, $fecha_inicio, $fecha_fin);
                PagoComision::create([
                    'fecha_inicio' => $fecha_inicio,
                    'fecha_fin' => $fecha_fin,
                    'chargeback' => $chargeback ? $chargeback[0]['total_chargeback'] : 0,
                    'vendedor_id' => $vendedor->id,
                    'valor' =>  $comisiones,
                ]);
            }
             $this->pagar_comisiones($vendedor->modalidad_id, $vendedor->id, $fecha_inicio, $fecha_fin);
             DB::commit();
            // PagoComision::insert($pagos_comision);
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ["error en calcular_ventas_comision", $e->getmessage(), $e->getLine()]);
        }
    }



    private function pagar_comisiones($modalidad, $vendedor_id, $fecha_inicio, $fecha_fin)
    {

        $pago_comision = PagoComision::where('vendedor_id', $vendedor_id)->get()->count();
        $limite_venta = 0;
        $query_ventas = Ventas::whereBetween('created_at', [$fecha_inicio, $fecha_fin])->where('vendedor_id', $vendedor_id);
        $comisiones = null;
        if ($pago_comision == 0) {
            $modalidad = Modalidad::where('id', $modalidad)->first();
            $limite_venta =  $modalidad != null ? $modalidad->umbral_minimo : 0;
            $comisiones = $query_ventas->orderBy('id')->skip($limite_venta)->take(999999)->get();
        } else {
            $comisiones = $query_ventas->get();
        }
        $ids = $comisiones->pluck('id');
        Ventas::whereIn('id', $ids)->update([
            'pago' => true,
        ]);
    }
    private function calcular_comisiones($empleado_id, $fecha_inicio, $fecha_fin)
    {
        try {
           DB::beginTransaction();
            $vendedor = Vendedor::where('empleado_id', $empleado_id)->first();
            $comisiones = null;
            $ventas = null;
            $pago_comision = $this->calcular_ventas_comision($empleado_id, $fecha_inicio, $fecha_fin, $vendedor);
            $modalidad = Modalidad::where('id', $vendedor->modalidad_id)->first();
            $limite_venta =  $modalidad != null ? $modalidad->umbral_minimo : 0;

            if ($vendedor->tipo_vendedor == 'VENDEDOR') {
                $ventas = $pago_comision['ventas']->orderBy('id')->skip($limite_venta)->take(999999)->get();
                $comisiones = array_reduce($ventas->toArray(), function ($carry, $item) {
                    return $carry + $item["comision_vendedor"];
                }, 0);
            } else {
                $ventas = $pago_comision['ventas']->orderBy('ventas_ventas.id')->get();
                $comisiones = array_reduce($ventas->toArray(), function ($carry, $item) use ($vendedor) {
                    $plan = ProductoVentas::select('plan_id')->where('id', $item['producto_id'])->first();
                    $plan = $plan != null ? $plan->plan_id : 0;
                    $forma_pago = $item['forma_pago'];
                    $tipo_vendedor= $vendedor->tipo_vendedor;
                    $comision_pagar = Comisiones::where('forma_pago', $forma_pago)->where('plan_id',$plan )->where('tipo_vendedor', $tipo_vendedor)->first();
                    $comision_pagar =  $comision_pagar != null ? $comision_pagar->comision:0;
                    return $carry + $comision_pagar;
                }, 0);
            }
            //  $this->pagar_comisiones($vendedor->modalidad_id, $vendedor->id, $fecha_inicio, $fecha_fin);
            DB::commit();
            return $comisiones;
        } catch (Exception $e) {
           DB::rollBack();
            Log::channel('testing')->info('Log', ["error en calcular_comision", $e->getmessage(), $e->getLine()]);
        }
    }
    public function calcular_ventas_comision($empleado_id, $fecha_inicio, $fecha_fin, Vendedor $vendedor)
    {
        try {
            DB::beginTransaction();
            $ventas = Ventas::whereBetween('fecha_activ', [$fecha_inicio, $fecha_fin]);
            $comisiones = PagoComision::where('fecha_inicio', $fecha_inicio)->where('fecha_fin', $fecha_fin);
            $pago_comision = null;
            if ($vendedor->tipo_vendedor == 'VENDEDOR') {
                $pago_comision = $comisiones->where('vendedor_id',  $vendedor->id)->get()->count();
                $ventas->with('producto')->where('vendedor_id', $vendedor->id);
            } else {
                $pago_comision = PagoComision::join('ventas_vendedor', 'ventas_pago_comision.vendedor_id', '=', 'ventas_vendedor.id')
                    ->where('ventas_vendedor.jefe_inmediato_id', $empleado_id)
                    ->where('fecha_inicio', $fecha_inicio)
                    ->where('fecha_fin', $fecha_fin)
                    ->get()
                    ->count();
                $ventas = Ventas::join('ventas_vendedor', 'ventas_ventas.vendedor_id', '=', 'ventas_vendedor.id')
                    ->where('ventas_vendedor.jefe_inmediato_id', $empleado_id)
                    ->whereBetween('fecha_activ', [$fecha_inicio, $fecha_fin]);
            }
            DB::commit();
            return compact('pago_comision', 'ventas');
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ["error en calcular_ventas_comision", $e->getmessage(), $e->getLine()]);
        }
    }

    public function update(PagoComisionRequest $request, PagoComision $pago_comision)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $pago_comision->update($datos);
            $modelo = new PagoComisionResource($pago_comision->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
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
