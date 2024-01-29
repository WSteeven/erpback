<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\PagoComisionRequest;
use App\Http\Resources\Ventas\PagoComisionResource;
use App\Models\Producto;
use App\Models\Ventas\Chargeback;
use App\Models\Ventas\Comision;
use App\Models\Ventas\Comisiones;
use App\Models\Ventas\Modalidad;
use App\Models\Ventas\PagoComision;
use App\Models\Ventas\ProductoVenta;
use App\Models\Ventas\ProductoVentas;
use App\Models\Ventas\Vendedor;
use App\Models\Ventas\Venta;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\App\VentasClaro\PagoComisionService;
use Src\Shared\Utils;

class PagoComisionController extends Controller
{
    private $entidad = 'PagoComision';
    private $servicio;
    public function __construct()
    {
        $this->servicio = new PagoComisionService();
        $this->middleware('can:puede.ver.pagos_comisiones')->only('index', 'show');
        $this->middleware('can:puede.crear.pagos_comisiones')->only('store');
        $this->middleware('can:puede.editar.pagos_comisiones')->only('update');
        $this->middleware('can:puede.eliminar.pagos_comisiones')->only('destroy');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = PagoComision::ignoreRequest(['campos'])->filter()->get();
        $results = PagoComisionResource::collection($results);
        return response()->json(compact('results'));
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
            throw $e;
        }
    }
    public function show(Request $request, PagoComision $pago_comision)
    {
        $modelo = new PagoComisionResource($pago_comision);

        return response()->json(compact('modelo'));
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
