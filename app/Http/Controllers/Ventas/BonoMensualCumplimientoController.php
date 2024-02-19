<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\BonoMensualCumplimientoRequest;
use App\Http\Resources\Ventas\BonoMensualCumplimientoResource;
use App\Models\Ventas\BonoMensualCumplimiento;
use App\Models\Ventas\BonoPorcentual;
use App\Models\Ventas\Bono;
use App\Models\Ventas\UmbralVenta;
use App\Models\Ventas\Vendedor;
use App\Models\Ventas\Venta;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\VentasClaro\BonoCumplimientoService;
use Src\Shared\Utils;

class BonoMensualCumplimientoController extends Controller
{
    private $entidad = 'Bono Mensual de Cumplimiento';
    private $servicio;
    public function __construct()
    {
        $this->servicio = new BonoCumplimientoService();
        $this->middleware('can:puede.ver.bonos_mensuales_cumplimientos')->only('index', 'show');
        $this->middleware('can:puede.crear.bonos_mensuales_cumplimientos')->only('store');
        $this->middleware('can:puede.editar.bonos_mensuales_cumplimientos')->only('update');
        $this->middleware('can:puede.eliminar.bonos_mensuales_cumplimientos')->only('destroy');
    }


    public function index(Request $request)
    {
        $results = [];
        $results = BonoMensualCumplimiento::ignoreRequest(['campos'])->filter()->get();
        $results = BonoMensualCumplimientoResource::collection($results);
        return response()->json(compact('results'));
    }


    public function store(BonoMensualCumplimientoRequest $request)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $this->servicio->calcularBonosMensuales($datos['mes']);
            $modelo = [];
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
            throw ValidationException::withMessages([
                'Error' => [$e->getMessage() . '. ' . $e->getLine()],
            ]);
        }
    }


    public function show(Request $request, BonoMensualCumplimiento $bono_mensual_cumplimiento)
    {
        $modelo = new BonoMensualCumplimientoResource($bono_mensual_cumplimiento);
        return response()->json(compact('modelo'));
    }


    public function update(BonoMensualCumplimientoRequest $request, BonoMensualCumplimiento $bono_mensual_cumplimiento)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $bono_mensual_cumplimiento->update($datos);
            $modelo = new BonoMensualCumplimientoResource($bono_mensual_cumplimiento->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, BonoMensualCumplimiento $bono_mensual_cumplimiento)
    {
        $bono_mensual_cumplimiento->delete();
        return response()->json(compact('bono_mensual_cumplimiento'));
    }


    public function marcarPagada(BonoMensualCumplimiento $bono)
    {
        try {
            if (!$bono->pagada) {
                $bono->pagada = !$bono->pagada;
                $bono->save();
                $modelo = new BonoMensualCumplimientoResource($bono->refresh());
                $mensaje = Utils::obtenerMensaje($this->entidad, 'update'); // 'El corte de pago de comisión ha sido actualizado con éxito';
                return response()->json(compact('modelo', 'mensaje'));
            } else {
                throw new Exception('Este bono ya ha sido marcado como pagado previamente');
            }
        } catch (\Throwable $e) {
            throw ValidationException::withMessages(['error' => $e->getMessage()]);
        }
    }
}
