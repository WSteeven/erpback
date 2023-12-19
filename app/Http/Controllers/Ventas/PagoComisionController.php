<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\PagoComisionRequest;
use App\Http\Resources\Ventas\PagoComisionResource;
use App\Models\Ventas\Chargebacks;
use App\Models\Ventas\Modalidad;
use App\Models\Ventas\PagoComision;
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
            DB::beginTransaction();
            $this->tabla_comisiones($datos['fecha_inicio'],$datos['fecha_fin']);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            $modelo = new PagoComision();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    private function tabla_comisiones($fecha_inicio,$fecha_fin)
    {
        $vendedor = Vendedor::get();
        $pagos_comision  = [];
        $limite_venta = 0;
        foreach ($vendedor as $vendedor) {
            $chargeback = Chargebacks::select( DB::raw('SUM(valor) AS total_chargeback'))
            ->join('ventas_ventas', 'ventas_chargebacks.venta_id', '=', 'ventas_ventas.id')
            ->where('ventas_ventas.vendedor_id', $vendedor->id)
            ->whereBetween('ventas_chargebacks.fecha',[$fecha_inicio,$fecha_fin])
            ->get();
            $comisiones = $this->calcular_comisiones($vendedor->modalidad_id, $vendedor->id, $fecha_inicio, $fecha_fin);
            $pagos_comision[]  = [
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'chargeback' => $chargeback? $chargeback[0]['total_chargeback']:0,
                'vendedor_id' => $vendedor->id,
                'valor' =>  $comisiones,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        $this->pagar_comisiones($vendedor->modalidad_id, $vendedor->id, $fecha_inicio, $fecha_fin);
        PagoComision::insert($pagos_comision);


    }
    private function pagar_comisiones($modalidad, $vendedor_id, $fecha_inicio, $fecha_fin)
    {
        $pago_comision= PagoComision::where('vendedor_id',$vendedor_id)->get()->count();
        $limite_venta = 0;
        $query_ventas = Ventas::whereBetween('created_at',[$fecha_inicio, $fecha_fin])->where('vendedor_id', $vendedor_id);
        $comisiones = null;
        if($pago_comision == 0){
            $modalidad = Modalidad::where('id',$modalidad)->first();
            $limite_venta =  $modalidad!=null? $modalidad->umbral_minimo:0;
            $comisiones = $query_ventas->orderBy('id')->skip($limite_venta)->take(999999)->get();

        }else{
            $comisiones = $query_ventas->get();
        }
        $ids = $comisiones->pluck('id');
        Ventas::whereIn('id', $ids)->update([
            'pago' => true,
        ]);

    }
    private function calcular_comisiones($modalidad, $vendedor_id, $fecha_inicio,$fecha_fin)
    {
        $comisiones = null;
        $pago_comision= $this->calcular_ventas_comision($vendedor_id,$fecha_inicio,$fecha_fin);
        if($pago_comision['pago_comision'] == 0){
            $modalidad = Modalidad::where('id',$modalidad)->first();
            $limite_venta =  $modalidad!=null? $modalidad->umbral_minimo:0;
            $comisiones = $pago_comision['query_ventas']->orderBy('id')->skip($limite_venta)->take(999999)->get();
        }else{
            $comisiones = $pago_comision['query_ventas']->get();
        }
        $comisiones = array_reduce($comisiones->toArray(), function ($carry, $item) {
            return $carry + $item["comision_vendedor"];
        }, 0);
        return $comisiones;

    }
public function calcular_ventas_comision($vendedor_id, $fecha_inicio, $fecha_fin){
    $query_ventas = Ventas::whereBetween('created_at',[ $fecha_inicio, $fecha_fin]);
    $query_comisiones = PagoComision::where('vendedor_id',$vendedor_id);
    $vendedor = Vendedor::where('id',$vendedor_id)->first();
    if ($vendedor->tipo_vendedor == 'VENDEDOR'){
        $pago_comision= $query_comisiones->get()->count();
        $limite_venta = 0;
        $query_ventas ->where('vendedor_id', $vendedor_id);
    }else{
        $pago_comision =  $query_comisiones->whereIn('vendedor',function($query)use($vendedor){
            $query->where('jefe_inmediato',$vendedor);
        } )->get()->count();
        $query_ventas = $query_ventas->whereIn('vendedor',function($query)use($vendedor){
            $query->where('jefe_inmediato',$vendedor);
        } );
    }
    return compact('pago_comision','query_ventas');
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
