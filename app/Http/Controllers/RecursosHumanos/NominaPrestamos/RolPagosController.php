<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\RolPagoRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\RolPagoResource;
use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\RolPago;
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
            foreach ($request->roles as $rol) {
                $this->GuardarRoles($request, $rol);
            }
            return;
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['ERROR en el insert de rol de pago', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    private function GuardarRoles(RolPagoRequest $request, $rolPago)
    {
            $datos = $request->validated();
            $datos['empleado_id'] = $rolPago['empleado'];
            $datos['dias'] = $rolPago['dias'];
            $datos['comision'] = $rolPago['comision'];
            $datos['alimentacion'] = $rolPago['alimentacion'];
            $datos['horas_extras'] = $rolPago['horas_extras'];


            $empleado = Empleado::find( $datos['empleado_id']);
        $fechaInicio = Carbon::parse($empleado->fecha_ingreso);
        $fechaFin = $fechaInicio->copy()->addMonths(13);
        $sueldo_basico = 450;
        $salario = $empleado->salario;
        $horas_extras =  $datos['horas_extras'];
        $comision = $datos['comision'];
        $sueldo = ($salario / 30) * $datos['dias'];
        $decimo_tercero = ($salario / 360) * $datos['dias'];
        $decimo_cuarto = ($sueldo_basico / 360) * $datos['dias'];
        $fondos_reserva = 0;
        /* if ($fechaFin->diffInMonths($fechaInicio) == 13) {
            // Han pasado 13 meses
            $fondos_reserva = $sueldo*8.33;
        }*/

        $ingresos = $sueldo + $decimo_tercero + $decimo_cuarto + $fondos_reserva + $datos['alimentacion'] + $horas_extras;
        $iess = ($sueldo + $horas_extras + $comision) * 0.0945;
        $anticipo = $sueldo * 0.40;
        $prestamo_quirorafario = 0;
        $prestamo_hipotecario = 0;
        $extension_conyugal = 0;
        $prestamo_empresarial = 0;
        $prestamo_empresarial = 0; //Prestamo::where('empleado_id',$this->empleado)->where('estado','activo')->where('tipo','empresarial')->sum('cuota');
        $sancion_pecuniaria = 0; //Sancion::where('empleado_id',$this->empleado)->where('estado','activo')->sum('monto');
        $descuento_herramientas = 0; //Herramienta::where('empleado_id',$this->empleado)->where('estado','activo')->sum('monto');
        $egreso = $iess + $anticipo + $prestamo_quirorafario + $prestamo_hipotecario + $extension_conyugal + $prestamo_empresarial + $sancion_pecuniaria + $descuento_herramientas;
        $total = abs($ingresos) - $egreso;
        $datos['salario'] = $salario;
        $datos['sueldo'] = $sueldo;
        $datos['decimo_tercero'] = $decimo_tercero;
        $datos['decimo_cuarto'] = $decimo_cuarto;
        $datos['fondos_reserva'] = $fondos_reserva;
        $datos['total_ingreso'] = $ingresos;
        $datos['iess'] = $iess;
        $datos['anticipo'] = $anticipo;
        $datos['prestamo_quirorafario'] = $prestamo_quirorafario;
        $datos['prestamo_hipotecario'] = $prestamo_hipotecario;
        $datos['extension_conyugal'] = $extension_conyugal;
        $datos['prestamo_empresarial'] = $prestamo_empresarial;
        $datos['sancion_pecuniaria'] = $sancion_pecuniaria;
        $datos['total_egreso'] = $sancion_pecuniaria;
        $datos['total'] = $total;
            DB::beginTransaction();
            $rolPago = RolPago::create($datos);
            $modelo = new RolPagoResource($rolPago);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

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
