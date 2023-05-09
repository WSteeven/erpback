<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\RolPagoRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\RolPagoResource;
use App\Models\RecursosHumanos\NominaPrestamos\RolPago;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class RolPagosController extends Controller
{
    private $entidad = 'Rol_de_pagos';
    public function __construct()
    {
        $this->middleware('can:puede.ver.rol_pago')->only('index', 'show');
        $this->middleware('can:puede.crear.rol_pago')->only('store');
    }

    public function index(Request $request)
    {
        $results = [];
        $results = RolPago::ignoreRequest(['campos'])->filter()->get();
        $results = RolPagoResource::collection($results);
        return response()->json(compact('results'));

    }

    public function create(Request $request)
    {
        $rolPago = new RolPago();
        $rolPago->nombre = $request->nombre;
        $rolPago->save();
        return $rolPago;
    }

    public function store(RolPagoRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $datos['empleado_id'] =  $request->safe()->only(['empleado'])['empleado'];

            $rolPago = RolPago::create($datos);
            $modelo = new RolPagoResource($rolPago);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR en el insert de permiso de empleado', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(RolPago $rolPago)
    {
        $modelo = new RolPagoResource($rolPago);
        return response()->json(compact('modelo'), 200);
    }

    public function update(Request $request, $rolPagoId)
    {
        $rolPago = RolPago::find($rolPagoId);
        $rolPago->nombre = $request->nombre;
        $rolPago->save();
        return $rolPago;
    }

    public function destroy($rolPagoId)
    {
        $rolPago = RolPago::find($rolPagoId);
        $rolPago->delete();
        return $rolPago;
    }

}
