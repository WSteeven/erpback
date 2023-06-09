<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\RolPagoRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\RolPagoResource;
use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\DescuentosGenerales;
use App\Models\RecursosHumanos\NominaPrestamos\EgresoRolPago;
use App\Models\RecursosHumanos\NominaPrestamos\ExtensionCoverturaSalud;
use App\Models\RecursosHumanos\NominaPrestamos\IngresoRolPago;
use App\Models\RecursosHumanos\NominaPrestamos\Multas;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoHipotecario;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoQuirorafario;
use App\Models\RecursosHumanos\NominaPrestamos\RolPago;
use App\Models\RecursosHumanos\NominaPrestamos\Rubros;
use Carbon\Carbon;
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

    public function store(RolPagoRequest $request)
    {
        try {
            $datos = $request->validated();
            $datos['empleado_id'] = $request->safe()->only(['empleado'])['empleado'];
            DB::beginTransaction();
            $rolPago = RolPago::create($datos);
            foreach ($request->ingresos as $ingreso) {
                $this->GuardarIngresos($ingreso, $rolPago);
            }
            foreach ($request->egresos as $egreso) {
                $this->GuardarEgresos($egreso, $rolPago);
            }
            $modelo = new RolPagoResource($rolPago);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['ERROR en el insert de rol de pago', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    private function GuardarIngresos($ingreso, $rolPago)
    {
        $datos = $ingreso;
        $datos['id_rol_pago'] =  $rolPago->id;
        DB::beginTransaction();
        $rolPago = IngresoRolPago::create($datos);
        DB::commit();
    }
    private function GuardarEgresos($egreso, $rolPago)
    {
        $datos = $egreso;
        $datos['id_rol_pago'] =  $rolPago->id;
        DB::beginTransaction();
        $id_descuento = $datos['id_descuento'];
        $entidad = null;
        switch ($datos['tipo']) {
            case 'DESCUENTO_GENERAL':
                $entidad = DescuentosGenerales::find($id_descuento);
                break;
            case 'MULTA':
                $entidad = Multas::find($id_descuento);
                break;
            default:
                break;
        }
        $rolPago = EgresoRolPago::crearEgresoRol($datos['id_rol_pago'], $datos['monto'], $entidad);
        DB::commit();
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
