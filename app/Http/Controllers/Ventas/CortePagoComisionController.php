<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\CortePagoComisionRequest;
use App\Http\Resources\Ventas\CortePagoComisionResource;
use App\Models\Ventas\CortePagoComision;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class CortePagoComisionController extends Controller
{
    private $entidad = 'Corte de Pago de Comisión';
    private $servicio;
    public function __construct()
    {
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
}
