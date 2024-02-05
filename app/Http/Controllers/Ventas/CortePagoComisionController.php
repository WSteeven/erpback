<?php

namespace App\Http\Controllers\Ventas;

use App\Exports\Ventas\CortePagoComisionExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\CortePagoComisionRequest;
use App\Http\Resources\Ventas\CortePagoComisionResource;
use App\Models\ConfiguracionGeneral;
use App\Models\Ventas\CortePagoComision;
use App\Models\Ventas\DetallePagoComision;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Src\App\VentasClaro\PagoComisionService;
use Src\Shared\Utils;

class CortePagoComisionController extends Controller
{
    private $entidad = 'Corte de Pago de Comisión';
    private $servicio;
    public function __construct()
    {
        $this->servicio = new PagoComisionService();
        $this->middleware('can:puede.ver.cortes_pagos_comisiones')->only('index', 'show');
        $this->middleware('can:puede.crear.cortes_pagos_comisiones')->only('store');
        $this->middleware('can:puede.editar.cortes_pagos_comisiones')->only('update');
        $this->middleware('can:puede.eliminar.cortes_pagos_comisiones')->only('destroy');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = CortePagoComision::ignoreRequest(['campos'])->filter()->get();
        $results = CortePagoComisionResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(CortePagoComisionRequest $request)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $corte = CortePagoComision::create($datos);
            // aqui se calcula los cortes para crear los respectivos ítems
            DetallePagoComision::crearComisionesEmpleados($corte);

            // throw new Exception('Error no controlado');
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            $modelo = new CortePagoComisionResource($corte);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw ValidationException::withMessages(['error' => $e->getMessage()]);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function show(Request $request, CortePagoComision $corte)
    {
        $modelo = new CortePagoComisionResource($corte);

        return response()->json(compact('modelo'));
    }

    // public function update(CortePagoComisionRequest $request, CortePagoComision $corte)
    // {
    //     try {
    //         DB::beginTransaction();
    //         $datos = $request->validated();
    //         $corte->update($datos);
    //         $modelo = new CortePagoComisionResource($corte->refresh());
    //         $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
    //         DB::commit();
    //         return response()->json(compact('mensaje', 'modelo'));
    //     } catch (Exception $e) {
    //         DB::rollback();
    //         return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
    //     }
    // }
    public function destroy(Request $request, CortePagoComision $corte)
    {
        $corte->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }

    /**
     * Anular un corte de pago
     */
    public function anular(Request $request, CortePagoComision $corte)
    {
        try {
            DB::beginTransaction();
            $request->validate(['causa_anulacion' => ['required', 'string']]);
            $corte->causa_anulacion = $request->causa_anulacion;
            $corte->estado = CortePagoComision::ANULADA;
            $corte->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['error' => $e->getMessage()]);
        }
        $modelo = new CortePagoComisionResource($corte->refresh());
        return response()->json(compact('modelo'));
    }

    public function imprimirExcel(CortePagoComision $corte)
    {
        try {
            $modelo = new CortePagoComisionResource($corte);
            $reporte = $modelo->resolve();
            Log::channel('testing')->info('Log', ["corte resource", $reporte]);
            return Excel::download(new CortePagoComisionExport($reporte), 'reporte.xlsx');
            // return Excel::download(new CortePagoComisionExport($reporte, $config), 'reporte.xlsx');
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ["error", $e->getLine(), $e->getMessage()]);
            throw ValidationException::withMessages(['error' => $e->getMessage()]);
        }
    }

    public function obtenerFechasDisponblesCortes()
    {
        try {
            $results = $this->servicio->fechasDisponiblesCorte();
        } catch (\Throwable $e) {
            throw ValidationException::withMessages(['error' => $e->getMessage()]);
        }
        return response()->json(compact('results'));
    }
}
