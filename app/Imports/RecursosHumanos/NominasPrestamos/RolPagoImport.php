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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;


class RolPagoImport implements ToModel, WithHeadingRow, WithValidation
{
    private $empleados;
    public $mes = "";
    public $es_quincena = false;
    public RolPagoMes $rolPagoMes;
    public function __construct($mes, RolPagoMes $rolPagoMes)
    {
        $this->mes = $mes;
        $this->empleados = Empleado::pluck('id', 'identificacion');
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
            DB::beginTransaction();
            $dias = $this->es_quincena ? 15 : 30;
            $identificacion = $row['identificacion'];
            if (!is_null($row['dias'])) {
                $dias = $row['dias'];
            }
            if (isset($this->empleados[$identificacion])) {
                $id_empleado = $this->empleados[$identificacion];
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
                DB::commit();
            } else {
                DB::rollBack();
                throw ValidationException::withMessages(['No existe empleado con numero de identificacion: ' . $identificacion]);
            }
        } catch (\Throwable $th) {
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
        try {
            DB::beginTransaction();
            $concepto_ingreso = ConceptoIngreso::where('abreviatura', 'ALI')->first();
            $id_rol_pago = $rol_pago?->id;
            $monto = is_null($valor_alimentacion) ? 0 : $valor_alimentacion;
            if ($monto !== 0) {
                IngresoRolPago::create([
                    'id_rol_pago' => $id_rol_pago,
                    'concepto' => $concepto_ingreso?->id,
                    'monto' => $monto,
                ]);
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage() . $e->getLine()],
            ]);
        }
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
        try {
            DB::beginTransaction();
            $difiess = DescuentosGenerales::where('abreviatura', 'DIFIESS')->first();
            $descuento = DescuentosGenerales::where('abreviatura', 'DESC')->first();
            $antAlimentacion = DescuentosGenerales::where('abreviatura', 'AALI')->first();
            $memo = Multas::where('abreviatura', 'DRINT')->first();
            $valor_difiess = is_null($valor_difiess) ? 0 : $valor_difiess;
            $valor_descuento = is_null($valor_descuento) ? 0 : $valor_descuento;
            $valor_antAlimentacion = is_null($valor_antAlimentacion) ? 0 : $valor_antAlimentacion;
            $valor_memo = is_null($valor_memo) ? 0 : $valor_memo;
            if ($valor_difiess !== 0) EgresoRolPago::crearEgresoRol($rol_pago, $valor_difiess, $difiess);
            if ($valor_descuento !== 0) EgresoRolPago::crearEgresoRol($rol_pago, $valor_descuento, $descuento);
            if ($valor_antAlimentacion !== 0) EgresoRolPago::crearEgresoRol($rol_pago, $valor_antAlimentacion, $antAlimentacion);
            if ($valor_memo !== 0) EgresoRolPago::crearEgresoRol($rol_pago, $valor_memo, $memo);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage() . $e->getLine()],
            ]);
        }
    }
    public function rules(): array
    {
        if($this->es_quincena){
            return [
                '*.identificacion' => ['required'],
                '*.salario' => ['required', 'numeric'],
                '*.sueldo' => ['required', 'numeric'],
                '*.xiiirol' => ['nullable', 'numeric'],
                '*.xivrol' => ['nullable', 'numeric'],
                '*.fdrarol' => ['nullable', 'numeric'],
                '*.ali' => ['nullable', 'numeric'],
                '*.iess' => ['nullable', 'numeric'],
                '*.prsqrg' => ['nullable', 'numeric'],
                '*.prhipo' => ['nullable', 'numeric'],
                '*.prestamo' => ['nullable', 'numeric'],
                '*.extconyuge' => ['nullable', 'numeric'],
                '*.anticipo' => ['nullable', 'numeric'],
                '*.antalimentacion' => ['nullable', 'numeric'],
                '*.descuento' => ['nullable', 'numeric'],
                '*.memo' => ['nullable', 'numeric'],
                '*.difiess' => ['nullable', 'numeric'],
            ];
        }else{
            return [
                '*.identificacion' => ['required'],
                '*.salario' => ['required', 'numeric'],
                '*.sueldo' => ['required', 'numeric'],
                '*.xiiirol' => ['required', 'numeric'],
                '*.xivrol' => ['required', 'numeric'],
                '*.fdrarol' => ['nullable', 'numeric'],
                '*.ali' => ['nullable', 'numeric'],
                '*.iess' => ['required', 'numeric'],
                '*.prsqrg' => ['nullable', 'numeric'],
                '*.prhipo' => ['nullable', 'numeric'],
                '*.prestamo' => ['nullable', 'numeric'],
                '*.extconyuge' => ['nullable', 'numeric'],
                '*.anticipo' => ['required', 'numeric'],
                '*.antalimentacion' => ['nullable', 'numeric'],
                '*.descuento' => ['nullable', 'numeric'],
                '*.memo' => ['nullable', 'numeric'],
                '*.difiess' => ['nullable', 'numeric'],
            ];
        }
    }
}
