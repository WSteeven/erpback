<?php

namespace Src\App\RecursosHumanos\NominaPrestamos;

use App\Mail\RolPagoEmail;
use App\Models\Departamento;
use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\ExtensionCoverturaSalud;
use App\Models\RecursosHumanos\NominaPrestamos\RolPago;
use App\Models\RecursosHumanos\NominaPrestamos\RolPagoMes;
use App\Models\RecursosHumanos\NominaPrestamos\Rubros;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Src\App\FondosRotativos\ReportePdfExcelService;

class NominaService
{
    private $mes;
    private $id_empleado;
    private Empleado $empleado;
    private $reporteService;

    public function __construct($mes = null)
    {
        $this->mes = $mes == null ? Carbon::now() : $mes;
        $this->empleado = new Empleado();
        $this->reporteService = new ReportePdfExcelService();
    }
    public function setMes($mes)
    {
        $this->mes = $mes;
    }
    public function setEmpleado($id_empleado)
    {
        $this->id_empleado = $id_empleado;
        $this->empleado = Empleado::where('id', $this->id_empleado)->first();
    }
    public function permisoEmpleado($recupero = 0, $todos = false, $pluck = false)
    {
        $query = DB::table('permiso_empleados')
            ->whereRaw('DATE_FORMAT(fecha_hora_inicio, "%Y-%m") <= ?', [$this->mes])
            ->whereRaw('DATE_FORMAT(fecha_hora_fin, "%Y-%m") >= ?', [$this->mes])
            ->where('recupero', $recupero);

        if ($todos) {
            $query->groupBy('empleado_id')
                ->select('empleado_id', DB::raw('SUM(DATEDIFF(fecha_hora_fin, fecha_hora_inicio) + 1) as total_dias_permiso'));

            if ($pluck) {
                return $query->pluck('total_dias_permiso', 'empleado_id');
            } else {
                return $query->get();
            }
        } else {
            $query->where('empleado_id',  $this->id_empleado)
                ->groupBy('empleado_id')
                ->select('empleado_id', DB::raw('SUM(DATEDIFF(fecha_hora_fin, fecha_hora_inicio) + 1) as total_dias_permiso'));

            return $query->first() != null ? $query->first()->total_dias_permiso : 0;
        }
    }
    public function extensionesCoberturaSalud($todos = false, $pluck = false)
    {
        $mes_convertido = Carbon::createFromFormat('Y-m', $this->mes)->format('m-Y');

        $query = ExtensionCoverturaSalud::where('mes', $mes_convertido);
        if ($todos) {
            $query->groupBy('empleado_id')
                ->select('empleado_id', DB::raw('SUM(aporte) as total_valor'));

            if ($pluck) {
                return $query->pluck('total_valor', 'empleado_id');
            } else {
                return $query->get();
            }
        } else {
            $extension_conyugal =  $query->where('empleado_id',  $this->id_empleado)
                ->groupBy('empleado_id')
                ->select('empleado_id', DB::raw('SUM(aporte) as total_valor'))->first();
            $suma =   $extension_conyugal == null ? 0 : $extension_conyugal->total_valor;
            return $suma != 0 ? $suma : 0;
        }
    }
    public static function  obtenerValorRubro($rubroId)
    {
        $rubro = Rubros::find($rubroId);
        return $rubro != null ? $rubro->valor_rubro : 0;
    }
    public  function calcularSupa()
    {
        return $this->empleado->supa;
    }
    public static function calcularSueldoBasico()
    {
        return NominaService::obtenerValorRubro(2);
    }

    public static function calcularPorcentajeIESS()
    {
        return NominaService::obtenerValorRubro(1) / 100;
    }

    public static function calcularPorcentajeAnticipo()
    {
        return NominaService::obtenerValorRubro(4) / 100;
    }
    public static function calcularPorcentajeFondoReserva()
    {
        return NominaService::obtenerValorRubro(5) / 100;
    }
    public  function calcularDias($cantidad_dias, $dias = 30)
    {
        // Fecha ingresada en formato dd-mm-yyyy
        $fechaIngresada = $this->empleado->fecha_ingreso;
        // Convierte la fecha ingresada a un objeto Carbon utilizando el formato 'd-m-Y'
        $fechaCarbon = Carbon::createFromFormat('d-m-Y', $fechaIngresada);
        $dato_mes = explode("-", $this->mes);
        $mes = $dato_mes[1].'-'.$dato_mes[0];
        // Obtiene la fecha actual
        $fecha_completa = $dias."-" .$mes;
        $fechaActual = Carbon::createFromFormat('d-m-Y', $fecha_completa);
        $diasRestantes = 0;
        // Verifica si la fecha ingresada pertenece al mes actual
        if ($fechaCarbon->isCurrentMonth()) {
            // Verifica si la fecha ingresada es anterior al día 15 del mes actual
            if ($fechaCarbon->day < $cantidad_dias && $fechaCarbon->day>1 ) {
                // Resta la fecha ingresada de la fecha del 15 del mes actual
                $diasRestantes = $fechaActual->day - $fechaCarbon->day;
            } else {
                // La fecha ingresada ya es igual o posterior al 15 del mes actual
                $diasRestantes = $cantidad_dias; // No quedan días hasta el 15 del mes actual
            }
        } else {
            // La fecha ingresada no pertenece al mes actual
            $diasRestantes = $cantidad_dias; // No se calculan días en este caso
        }
        return $diasRestantes;
    }


    public function calcularSueldo($dias = 30, $es_quincena = false, $sueldo = 0)
    {

        $salario_diario = $this->empleado->salario / 30;
        if ($es_quincena) {
            $sueldo = $sueldo !== 0 ? $sueldo : $this->empleado->salario * NominaService::calcularPorcentajeAnticipo();
        } else {
            $dias_trabajados = $dias - $this->permisoEmpleado();
            $sueldo = $salario_diario * $dias_trabajados;
        }
        return $sueldo;
    }
    public function calcularSalario()
    {
        return $this->empleado->salario;
    }
    public function calcularAporteIESS($dias = 30)
    {
        $sueldo = $this->calcularSueldo($dias);
        $iess = ($sueldo) * NominaService::calcularPorcentajeIESS();
        return floatval(number_format($iess, 2));
    }
    public function calcularDecimo($tipo, $dias,$es_vendedor_medio_tiempo = false)
    {
        switch ($tipo) {
            case 3:
                return ($this->empleado->salario / 360) * $dias;
                break;
            case 4:
                if($es_vendedor_medio_tiempo){
                    return (NominaService::calcularSueldoBasico() / 360) * 15;
                }else{
                    return (NominaService::calcularSueldoBasico() / 360) * $dias;
                }
                break;
        }
    }
    public function calcularAnticipo()
    {
        $mes_rol_anterior = Carbon::createFromFormat('Y-m', $this->mes)->format('m-Y');
        $rol_usuario = RolPago::join('rol_pago_mes', 'rol_pago.rol_pago_id', '=', 'rol_pago_mes.id')
            ->where('rol_pago.empleado_id', $this->id_empleado)
            ->where('rol_pago_mes.mes', $mes_rol_anterior)
            ->where('rol_pago_mes.es_quincena', 1)
            ->select('rol_pago.*')
            ->first();
        return $rol_usuario != null ? $rol_usuario->total : 0;
    }
    public function calcularFondosReserva($dias = 30)
    {
        $fondosDeReserva = 0;
        // Obtén la fecha de ingreso del empleado y conviértela a un objeto Carbon
        $fechaIngreso = Carbon::parse($this->empleado->fecha_vinculacion);
        // Obtén la fecha actual
        $hoy = Carbon::now();
        // Calcula la diferencia en días entre las dos fechas
        $diasTrabajados = $hoy->diffInDays($fechaIngreso);
        if ($diasTrabajados >= 366 && $this->empleado->acumula_fondos_reserva == 0) {
            $fondosDeReserva = $this->calcularSueldo($dias) * NominaService::calcularPorcentajeFondoReserva(); // 8.33% del sueldo
        }
        return floatval(number_format($fondosDeReserva, 2));
    }
    public function enviar_rol_pago($rolPagoId, $destinatario)
    {
        $nombre_reporte = 'rol_pagos';
        $roles_pagos = RolPago::where('id', $rolPagoId)->get();
        $results = RolPago::empaquetarListado($roles_pagos);
        $recursosHumanos = Departamento::where('id', 7)->first()->responsable_id;
        $responsable = Empleado::where('id', $recursosHumanos)->first();
        $reportes =  ['roles_pago' => $results, 'responsable' => $responsable];
        $vista = 'recursos-humanos.rol_pagos';
        $pdfContent = $this->reporteService->enviar_pdf('A5', 'landscape', $reportes, $vista);
        $user = User::where('id', $destinatario->usuario_id)->first();
        Mail::to($user->email)
        ->send(new RolPagoEmail($reportes, $pdfContent, $destinatario, $results[0]['rol_firmado']));

    }
}
