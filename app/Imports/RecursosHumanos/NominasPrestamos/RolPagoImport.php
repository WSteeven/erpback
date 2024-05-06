<?php

namespace App\Imports\RecursosHumanos\NominasPrestamos;

use App\Models\Empleado;
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
            Log::channel('testing')->info('Log', ['identificacion', isset($this->empleados[$identificacion])]);

            if (isset($this->empleados[$identificacion])) {
                $id_empleado = $this->empleados[$identificacion];
                $this->nominaService->setEmpleado($id_empleado);
                $this->prestamoService->setEmpleado($id_empleado);
                $obtener_ingresos = [];
                $obtener_egresos = [];
                $supa =0;
                if (!$this->es_quincena) {
                    $obtener_ingresos = $this->obtenerIngresos($row['ali']);
                    $obtener_egresos = $this->obtenerEgresos( $row['antAlimentacion'], $row['descuento'], $row['memo'], $row['difiess']);
                    $supa =  !is_null($row['supa']) ? $row['supa']:0;
                }
                $sueldo = $row['salario'];
                $salario = $row['sueldo'];
                $decimo_tercero = $this->es_quincena ? 0 : $row['decimo_tercero'];
                $decimo_cuarto = $this->es_quincena ? 0 : $row['decimo_cuarto'];
                $fondos_reserva = $this->es_quincena ? 0 :$row['fdarol'];
                $bono_recurente = 0;
                $bonificacion =  0;
                $totalIngresos =  $this->es_quincena ? 0 : $totalIngresos = !empty($obtener_ingresos)
                    ? array_reduce($obtener_ingresos, function ($acumulado, $ingreso) {
                        return $acumulado + (float) $ingreso['monto'];
                    }, 0)
                    : 0;
                $ingresos = $this->es_quincena ? $sueldo : $sueldo + $decimo_tercero + $decimo_cuarto + $fondos_reserva + $bonificacion + $bono_recurente + $totalIngresos;
                $iess =  $this->es_quincena ? 0 : $row['iess'];
                $anticipo = $this->es_quincena ? 0 : $this->nominaService->calcularAnticipo();
                $prestamo_quirorafario =   $this->es_quincena ? 0 : $row['prsqrg'];
                $prestamo_hipotecario =  $this->es_quincena ? 0 : $row['prhipo'];
                $extension_conyugal =  $this->es_quincena ? 0 :  $row['extconyugal'];
                $prestamo_empresarial = $this->es_quincena ? 0 : $row['prestamo'];
                $totalEgresos = $totalEgresos = !empty($obtener_egresos)
                    ? array_reduce($obtener_egresos, function ($acumulado, $egreso) {
                        return $acumulado + (float) $egreso['monto'];
                    }, 0) : 0;
                $egreso = $this->es_quincena ? 0 : $iess + $anticipo + $prestamo_quirorafario + $prestamo_hipotecario + $extension_conyugal + $prestamo_empresarial + $totalEgresos + $supa;
                $total = abs($ingresos) - $egreso;
                RolPago::create([
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
            }
        } catch (\Throwable $th) {
            Log::channel('testing')->info('Log', ['error', $th]);

            throw $th;
        }
    }
    public function rules(): array
    {
        return [
            '*.identificacion' => ['required'],
        ];
    }
}
