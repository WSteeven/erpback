<?php

namespace App\Imports\RecursosHumanos\NominasPrestamos;

use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\ConceptoIngreso;
use App\Models\RecursosHumanos\NominaPrestamos\DescuentosGenerales;
use App\Models\RecursosHumanos\NominaPrestamos\EgresoRolPago;
use App\Models\RecursosHumanos\NominaPrestamos\IngresoRolPago;
use App\Models\RecursosHumanos\NominaPrestamos\Multas;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoQuirorafario;
use App\Models\RecursosHumanos\NominaPrestamos\RolPago;
use App\Models\RecursosHumanos\NominaPrestamos\RolPagoMes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Src\App\RecursosHumanos\NominaPrestamos\NominaService;
use Src\App\RecursosHumanos\NominaPrestamos\PrestamoService;

class RolPagoImport implements ToModel, WithHeadingRow, WithValidation
{
    private $empleados;
    private $nominaService;
    public $mes = "";
    public $prestamoService;
    public $es_quincena = false;
    public RolPagoMes $rolPagoMes;
    public function __construct($mes, RolPagoMes $rolPagoMes)
    {
        $this->mes = $mes;
        $this->empleados = Empleado::pluck('id', 'identificacion');
        $this->nominaService = new NominaService($this->mes);
        $this->prestamoService = new PrestamoService($this->mes);
        $this->rolPagoMes =  $rolPagoMes;
        $this->es_quincena = $rolPagoMes->es_quincena;
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            $dias = $this->es_quincena ? 15 : 30;
            $identificacion = $row['identificacion'];
            if (!is_null($row['dias'])) {
                $dias = $row['dias'];
            }
            if (isset($this->empleados[$identificacion])) {
                $id_empleado = $this->empleados[$identificacion];
                $this->nominaService->setEmpleado($id_empleado);
                $this->prestamoService->setEmpleado($id_empleado);
                $obtener_ingresos = [];
                $obtener_egresos = [];
                $supa = 0;
                if (!$this->es_quincena) {
                    $obtener_ingresos = $this->obtenerIngresos($row['ali']);
                    $obtener_egresos = $this->obtenerEgresos($row['antalimentacion'], $row['descuento'], $row['memo'], $row['difiess']);
                    if (isset($row['supa'])) $supa =  !is_null($row['supa']) ? $row['supa'] : 0;
                }
                $sueldo = $row['salario'];
                $salario = $row['sueldo'];
                $decimo_tercero = $this->es_quincena  ? 0 : $row['xiiirol'];
                $decimo_cuarto = $this->es_quincena  ? 0 : $row['xivrol'];
                $fondos_reserva = $this->es_quincena || is_null($row['fdrarol']) ? 0 : $row['fdrarol'];
                $bono_recurente = 0;
                $bonificacion =  0;
                $totalIngresos =  $this->es_quincena ? 0 : $totalIngresos = !empty($obtener_ingresos)
                    ? array_reduce($obtener_ingresos, function ($acumulado, $ingreso) {
                        return $acumulado + (float) $ingreso['monto'];
                    }, 0)
                    : 0;
                $ingresos = $this->es_quincena ? $sueldo : $sueldo + $decimo_tercero + $decimo_cuarto + $fondos_reserva + $bonificacion + $bono_recurente + $totalIngresos;
                $iess =  $this->es_quincena ? 0 : $row['iess'];
                $anticipo = $this->es_quincena ? 0 :  $row['anticipo'];
                $prestamo_quirorafario =   $this->es_quincena || is_null($row['prsqrg']) ? 0 : $row['prsqrg'];
                $prestamo_hipotecario =  $this->es_quincena  || is_null($row['prhipo']) ? 0 : $row['prhipo'];
                $extension_conyugal =  $this->es_quincena || is_null($row['extconyuge']) ? 0 :  $row['extconyuge'];
                $prestamo_empresarial = $this->es_quincena || is_null($row['prestamo']) ? 0 : $row['prestamo'];
                $totalEgresos = $totalEgresos = !empty($obtener_egresos)
                    ? array_reduce($obtener_egresos, function ($acumulado, $egreso) {
                        return $acumulado + (float) $egreso['monto'];
                    }, 0) : 0;
                $egreso = $this->es_quincena ? 0 : $iess + $anticipo + $prestamo_quirorafario + $prestamo_hipotecario + $extension_conyugal + $prestamo_empresarial + $totalEgresos + $supa;
                $total = abs($ingresos) - $egreso;
                $rol_pago = RolPago::create([
                    'rol_pago_id' => $this->rolPagoMes->id,
                    'empleado_id' => $id_empleado,
                    'dias' => $dias,
                    'mes' => $this->mes,
                    'salario' => $salario,
                    'sueldo' => $sueldo,
                    'decimo_tercero' => $decimo_tercero,
                    'decimo_cuarto' => $decimo_cuarto,
                    'fondos_reserva' => $fondos_reserva,
                    'total_ingreso' => $ingresos,
                    'iess' => $iess,
                    'anticipo' => $anticipo,
                    'prestamo_quirorafario' => $prestamo_quirorafario,
                    'prestamo_hipotecario' => $prestamo_hipotecario,
                    'extension_conyugal' => $extension_conyugal,
                    'prestamo_empresarial' => $prestamo_empresarial,
                    'supa' => $supa,
                    'total_egreso' => $egreso,
                    'total' => $total,
                ]);
                $this->guardarIngresos($rol_pago, $row['ali']);
                $this->guardarEgresos($rol_pago, $row['antalimentacion'], $row['descuento'], $row['memo'], $row['difiess']);
            }
        } catch (\Throwable $th) {
            Log::channel('testing')->info('Log', ['error', $th->getMessage(), $th->getLine()]);

            throw $th;
        }
    }
    public function obtenerIngresos($valor_alimentacion)
    {
        $concepto_ingreso = ConceptoIngreso::where('abreviatura', 'ALI')->first();
        $id_rol_pago = $this->rolPagoMes?->id;
        $monto = is_null($valor_alimentacion) ? 0 : $valor_alimentacion;
        return [
            [
                'id_rol_pago' => $id_rol_pago,
                'concepto' => $concepto_ingreso?->id,
                'monto' => $monto,
            ]
        ];
    }
    public function guardarIngresos(RolPago $rol_pago, $valor_alimentacion)
    {
        $concepto_ingreso = ConceptoIngreso::where('abreviatura', 'ALI')->first();
        $id_rol_pago = $rol_pago?->id;
        $monto = is_null($valor_alimentacion) ? 0 : $valor_alimentacion;
        IngresoRolPago::create([
            'id_rol_pago' => $id_rol_pago,
            'concepto' => $concepto_ingreso?->id,
            'monto' => $monto,
        ]);
    }
    public function obtenerEgresos($valor_antAlimentacion, $valor_descuento, $valor_memo, $valor_difiess)
    {
        $valor_difiess = is_null($valor_difiess) ? 0 : $valor_difiess;
        $valor_descuento = is_null($valor_descuento) ? 0 : $valor_descuento;
        $valor_antAlimentacion = is_null($valor_antAlimentacion) ? 0 : $valor_antAlimentacion;
        $valor_memo = is_null($valor_memo) ? 0 : $valor_memo;
        return  [
            [
                'id_rol_pago' => $this->rolPagoMes?->id,
                'empleado_id' =>  $this->rolPagoMes->empleado_id,
                'monto' => $valor_difiess,
            ],
            [
                'id_rol_pago' => $this->rolPagoMes?->id,
                'empleado_id' =>  $this->rolPagoMes->empleado_id,
                'monto' => $valor_descuento,
            ],
            [
                'id_rol_pago' => $this->rolPagoMes?->id,
                'empleado_id' =>  $this->rolPagoMes->empleado_id,
                'monto' => $valor_antAlimentacion,
            ],
            [
                'id_rol_pago' => $this->rolPagoMes?->id,
                'empleado_id' =>  $this->rolPagoMes->empleado_id,
                'monto' => $valor_memo,
            ],
        ];
    }
    public function guardarEgresos(RolPago $rol_pago, $valor_antAlimentacion, $valor_descuento, $valor_memo, $valor_difiess)
    {
        $difiess = DescuentosGenerales::where('abreviatura', 'DIFIESS')->first();
        $descuento = DescuentosGenerales::where('abreviatura', 'DESC')->first();
        $antAlimentacion = DescuentosGenerales::where('abreviatura', 'AALI')->first();
        $memo = Multas::where('abreviatura', 'DRINT')->first();
        $valor_difiess = is_null($valor_difiess) ? 0 : $valor_difiess;
        $valor_descuento = is_null($valor_descuento) ? 0 : $valor_descuento;
        $valor_antAlimentacion = is_null($valor_antAlimentacion) ? 0 : $valor_antAlimentacion;
        $valor_memo = is_null($valor_memo) ? 0 : $valor_memo;
        EgresoRolPago::crearEgresoRol($rol_pago, $valor_difiess, $difiess);
        EgresoRolPago::crearEgresoRol($rol_pago, $valor_descuento, $descuento);
        EgresoRolPago::crearEgresoRol($rol_pago, $valor_antAlimentacion, $antAlimentacion);
        EgresoRolPago::crearEgresoRol($rol_pago, $valor_memo, $memo);
    }
    public function rules(): array
    {
        return [
            '*.identificacion' => ['required'],
        ];
    }
}
