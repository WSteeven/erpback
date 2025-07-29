<?php

namespace Src\App\RecursosHumanos\NominaPrestamos;

use App\Mail\RolPagoEmail;
use App\Models\Autorizacion;
use App\Models\Departamento;
use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\ConceptoIngreso;
use App\Models\RecursosHumanos\NominaPrestamos\Descuento;
use App\Models\RecursosHumanos\NominaPrestamos\EgresoRolPago;
use App\Models\RecursosHumanos\NominaPrestamos\ExtensionCoverturaSalud;
use App\Models\RecursosHumanos\NominaPrestamos\IngresoRolPago;
use App\Models\RecursosHumanos\NominaPrestamos\RolPago;
use App\Models\RecursosHumanos\NominaPrestamos\RolPagoMes;
use App\Models\RecursosHumanos\NominaPrestamos\Rubros;
use App\Models\RecursosHumanos\NominaPrestamos\ValorEmpleadoRolMensual;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Src\App\SystemNotificationService;
use Src\App\FondosRotativos\ReportePdfExcelService;
use Throwable;

class NominaService
{
    private string $mes;
    private int $id_empleado;
    private Empleado $empleado;
    private ReportePdfExcelService $reporteService;
    private RolPago $rolPago;

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

    public function setEmpleado(int $id_empleado)
    {
        $this->id_empleado = $id_empleado;
        $this->empleado = Empleado::where('id', $this->id_empleado)->first();
    }

    public function getEmpleado()
    {
        return $this->empleado;
    }

    public function setRolPago(RolPagoMes $rol_pago_mes)
    {
        $rolPago = RolPago::where('empleado_id', $this->empleado->id)->where('rol_pago_id', $rol_pago_mes->id)->first();
        if ($rolPago)
            $this->rolPago = $rolPago;
    }

//    public function setVendedorMedioTiempo($es_vendedor_medio_tiempo)
//    {
//        if ($this->rolPago) {
//            $this->rolPago->es_vendedor_medio_tiempo = $es_vendedor_medio_tiempo;
//        }
//    }

//    public function getRolPago()
//    {
//        return $this->rolPago;
//    }

    public function permisoEmpleado($recupero = 0, $todos = false, $pluck = false)
    {
        $query = DB::table('permiso_empleados')
            ->whereRaw('DATE_FORMAT(fecha_hora_inicio, "%Y-%m") <= ?', [$this->mes])
            ->whereRaw('DATE_FORMAT(fecha_hora_fin, "%Y-%m") >= ?', [$this->mes])
            ->where('estado_permiso_id', Autorizacion::APROBADO_ID)
            ->where('recupero', $recupero)
            ->where('cargo_vacaciones', false);

        if ($todos) {
            $query->groupBy('empleado_id')
                ->select('empleado_id', DB::raw('SUM(DATEDIFF(fecha_hora_fin, fecha_hora_inicio) + 1) as total_dias_permiso'));

            if ($pluck) {
                return $query->pluck('total_dias_permiso', 'empleado_id');
            } else {
                return $query->get();
            }
        } else {
            $query->where('empleado_id', $this->id_empleado)
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
                ->select(['empleado_id', DB::raw('SUM(aporte) as total_valor')]);

            if ($pluck) {
                return $query->pluck('total_valor', 'empleado_id');
            } else {
                return $query->get();
            }
        } else {
            $extension_conyugal = $query->where('empleado_id', $this->id_empleado)
                ->groupBy('empleado_id')
                ->select(['empleado_id', DB::raw('SUM(aporte) as total_valor')])->first();
            $suma = $extension_conyugal == null ? 0 : $extension_conyugal->total_valor;
            return $suma != 0 ? $suma : 0;
        }
    }

    public static function obtenerValorRubro($rubro_id)
    {
        $rubro = Rubros::find($rubro_id);
        return $rubro != null ? $rubro->valor_rubro : 0;
    }

    public function calcularSupa()
    {
        return $this->empleado->supa;
    }

    public static function calcularSueldoBasico()
    {
        return NominaService::obtenerValorRubro(Rubros::SBU); //2);
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

    /**
     * @throws Exception
     */
    public function calcularDias(int $cantidad_dias)
    {
        // Convierte la fecha de ingreso de un empleado a un objeto Carbon utilizando el formato 'd-m-Y'
        $fechaIngresada = Carbon::createFromFormat('Y-m-d', $this->empleado->fecha_ingreso);
        // Verifica si la fecha ingresada pertenece al mes actual
        if ($fechaIngresada->isCurrentMonth()) {
            // Verifica si la fecha ingresada es anterior al día 15 del mes actual
            // 18 de mes, fecha actual <15
            if ($fechaIngresada->day < $cantidad_dias) {
                // Resta la fecha ingresada de la fecha del 15 del mes actual
                $diasRestantes = $cantidad_dias - $fechaIngresada->day + 1;
            } else {
                // La fecha ingresada ya es igual o posterior al 15 del mes actual
//                $diasRestantes = $cantidad_dias; // No quedan días hasta el 15 del mes actual
                throw new Exception('No se puede calcular días sobre una fecha de ingreso posterior a la fecha actual. Revisa la fecha de ingreso del empleado ' . Empleado::extraerNombresApellidos($this->empleado) . ' cuya fecha de ingreso es: ' . $fechaIngresada);
            }
        } else {
            // La fecha ingresada no pertenece al mes actual
            $diasRestantes = $cantidad_dias; // No se calculan días en este caso
        }
        return $diasRestantes;
    }


    /**
     * @throws Throwable
     */
    public function calcularSueldo($dias = 30, $es_quincena = false, $sueldo = 0)
    {
        $salario_diario = $this->empleado->salario / 30;
        if ($es_quincena) {
            $sueldo = $sueldo <= 0 ? $this->empleado->salario * NominaService::calcularPorcentajeAnticipo() : $sueldo;
            // $sueldo = $sueldo !== 0 ? $sueldo : $this->empleado->salario * NominaService::calcularPorcentajeAnticipo();
        } else {
//            Log::channel('testing')->info('Log', [Empleado::extraerNombresApellidos($this->empleado), $this->empleado->salario, $dias, $this->permisoEmpleado()]);
            $dias_trabajados = $dias - $this->permisoEmpleado();
            $sueldo = $salario_diario * $dias_trabajados;
        }
        if ($sueldo === 0) {
//            Log::channel('testing')->info('Log', ['ID this->rolpago', $this->rolPago]);
            $sueldo = $this->calculoSueldoRolPago($es_quincena, $dias);
        }
        // Log::channel('testing')->info('Log', ['DATOS',  $this->empleado->nombres, $this->rolPago, $dias, $sueldo]);
        return $sueldo;
    }

    public function calculoSueldoRolPago($es_quincena, $dias = 30)
    {
        // Calcula el número de días trabajados
        $dias_quincena = $es_quincena ? 15 : 0;

        if ($this->rolPago->medio_tiempo || $this->empleado->tipo_contrato == 3) {
            $dias_quincena = 0;
        }
        $dias_totales = $dias + $dias_quincena;
        // Calcula el salario diario
        $sueldo_diario = ($this->empleado->salario / 30) * $dias_totales;
        // Calcula el total del salario
        //  $total_sueldo = 0;
        switch ($this->empleado->tipo_contrato) {
            case 3:
                $total_sueldo = ($this->empleado->salario * $this->calcularPorcentajeAnticipo()) / 15 * $dias;
                break;
            default:
                if ($this->rolPago->es_vendedor_medio_tiempo) {

                    $porcentaje = $this->rolPago->porcentaje_quincena ? $this->rolPago->porcentaje_quincena / 100 : 0;
                    $total_sueldo = $es_quincena ? ($this->empleado->salario * 0.5) * $porcentaje : $sueldo_diario;
                } else {
                    $total_sueldo = $es_quincena ? $sueldo_diario * $this->calcularPorcentajeAnticipo() : $sueldo_diario;
                }
                break;
        }
        return number_format($total_sueldo, 2);
    }

    /**
     * @return float
     */
    public function calcularSalario()
    {
        return (float)$this->empleado->salario;
    }

    public function obtener_total_descuentos_multas()
    {
        return EgresoRolPago::where('id_rol_pago', $this->rolPago->id)->sum('monto');
    }

    public function obtener_total_ingresos()
    {
        return IngresoRolPago::where('id_rol_pago', $this->rolPago->id)->sum('monto');
    }

    /**
     * @throws Throwable
     */
    public function registrarIngresosProgramados(RolPagoMes $rol_mes)
    {
//        Log::channel('testing')->info('Log', ['registrarIngresosProgramados', $rol_mes]);
        $mes = Carbon::createFromFormat('m-Y', $rol_mes->mes)->format('Y-m');
        try {
            $valores = ValorEmpleadoRolMensual::where('mes', $mes)
                ->where('tipo', ValorEmpleadoRolMensual::INGRESO)
                ->whereNull('rol_pago_id')->get();
            foreach ($valores as $valor) {
                $rol_empleado = RolPago::where('empleado_id', $valor->empleado_id)->where('rol_pago_id', $rol_mes->id)->first();
                IngresoRolPago::create([
                    'concepto' => class_basename($valor->model_type) == 'Vacacion' ? ConceptoIngreso::getOrCreateConceptoVacacion() : ConceptoIngreso::BONIFICACION_ID,
                    'id_rol_pago' => $rol_empleado->id,
                    'monto' => $valor->monto
                ]);
                // se setea en el valor el rol_pago_id encontrado
                $valor->rol_pago_id = $rol_empleado->id;
                $valor->save();
            }
        } catch (Exception $ex) {
            Log::channel('testing')->error('Log', ['error registrarIngresosProgramados', $ex->getMessage(), $ex->getLine()]);
            throw $ex;
        }
    }

//    public function actualizarIngresosProgramadosAlFinalizarRolPago(RolPagoMes $rol_mes)
//    {
//        $ids_roles_empleados = RolPago::where('rol_pago_id', $rol_mes->id)->pluck('id');
//        $valores = ValorEmpleadoRolMensual::where('tipo', ValorEmpleadoRolMensual::INGRESO)
//            ->whereIn('rol_pago_id', $ids_roles_empleados)->get();
//        foreach ($valores as $valor) {
//            $entidad = match ($valor->model_type) {
//                Vacacion::class => Vacacion::find($valor->model_id),
//                default => ValorEmpleadoRolMensual::find($valor->id)
//            };
////            Log::channel('testing')->info('Log', ['Relacion con el modelo externo es?', $valor, $entidad]);
//        }
//    }

    public function registrarEgresosProgramados(RolPagoMes $rol_mes)
    {
        // Obtenemos el mes del rol
        $mes = Carbon::createFromFormat('m-Y', $rol_mes->mes)->format('Y-m');
//        Log::channel('testing')->info('Log', ['mes del rol del empleado', $mes]);
        $roles_empleados = RolPago::where('rol_pago_id', $rol_mes->id)->get();
//        Log::channel('testing')->info('Log', ['roles_empleados', $roles_empleados]);
        foreach ($roles_empleados as $rol_empleado) {
            // Buscamos en la tabla de descuentos para ver si hay cuotas con el mes actual
//            Log::channel('testing')->info('Log', ['rol_empleado', $rol_empleado->id, ]);
            $descuentos = Descuento::where('pagado', false)
                ->where('empleado_id', $rol_empleado->empleado_id)
                ->where('mes_inicia_cobro', '<=', $mes)->get();
//            if ($descuentos->count() > 0) Log::channel('testing')->info('Log', ['descuentos', $descuentos]);
            // Recorremos los descuentos para ver las cuotas por cada uno y tomarlas para registrar esos egresos
            foreach ($descuentos as $descuento) {
                $cuota = $descuento->cuotas()->where('pagada', false)->where('mes_vencimiento', $mes)->first();
//                Log::channel('testing')->info('Log', ['Valor cuota a pagar', $cuota->valor_cuota]);
//                Log::channel('testing')->info('Log', ['Rol Pago Individual', $rol_empleado]);
                EgresoRolPago::crearEgresoRol($rol_empleado, $cuota->valor_cuota, $cuota);
            }
        }
    }

    public function actualizarEgresosProgramadosAlFinalizarRolPago(RolPagoMes $rol_mes)
    {
        $mes = Carbon::createFromFormat('m-Y', $rol_mes->mes)->format('Y-m');
        //$ids_roles_empleados = RolPago::where('rol_pago_id', $rol_mes->id)->pluck('id');
        $ids_empleados = RolPago::where('rol_pago_id', $rol_mes->id)->pluck('empleado_id');
        $descuentos = Descuento::whereIn('empleado_id', $ids_empleados)->get();
        foreach ($descuentos as $descuento) {
            $cuota = $descuento->cuotas()->where('pagada', false)->where('mes_vencimiento', $mes)->first();
            $cuota?->update(['pagada' => true, 'comentario' => 'PAGADO EN ROL DEL MES ' . $mes]);
            if (!$descuento->pagado && $descuento->cuotas()->where('pagada', false)->count() === 0) $descuento->update(['pagado' => true]);
        }
    }



    /**
     * @throws Exception
     */
//    public function calcularDiasRol($cantidad_dias)
//    {
//        $fechaIngresada = $this->empleado->fecha_ingreso;
//        $fechaCarbon = Carbon::createFromFormat('d-m-Y', $fechaIngresada);
//        if ($fechaCarbon->isCurrentMonth()) {
//            $dias = $this->calcularDias($cantidad_dias);
//        } else {
//            $dias = $this->rolPago->dias;
//        }
//        return $dias;
//    }

    /**
     * @throws Throwable
     */
    public function calcularAporteIESS($dias = 30)
    {
        $sueldo = $this->calcularSueldo($dias);
        $iess = ($sueldo) * NominaService::calcularPorcentajeIESS();
        return floatval(number_format($iess, 2));
    }

    /**
     * @throws Throwable
     */
    public function calcularDecimo(int $tipo, int $dias)
    {
//        $es_vendedor_medio_tiempo = false;
//        if (isset($this->rolPago->es_vendedor_medio_tiempo)) {
//            $es_vendedor_medio_tiempo = $this->rolPago->es_vendedor_medio_tiempo;
//        }
        return match ($tipo) {
            3 => number_format(($this->calcularSueldo($dias) / 12), 2),
            4 => (NominaService::calcularSueldoBasico() / 360) * $dias,
            default => 0,
        };
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

    /**
     * @throws Throwable
     */
    public function calcularFondosReserva($dias = 30)
    {
//        Log::channel('testing')->info('Log', ['dias', $dias]);
        $fondosDeReserva = 0;
        // Obtén la fecha de ingreso del empleado y conviértela a un objeto Carbon
        $fechaIngreso = Carbon::parse($this->empleado->fecha_vinculacion);
//        Log::channel('testing')->info('Log', ['fecha_vinculacion', $fechaIngreso]);
        // Obtén la fecha actual
        $mes = new Carbon($this->mes);
        $hoy = $mes->endOfMonth();
        // Calcula la diferencia en días entre las dos fechas
//        $diasTrabajados = $hoy->diffInDays($fechaIngreso);
        $mesesTrabajados = $hoy->diffInMonths($fechaIngreso);
//        Log::channel('testing')->info('Log', ['hoy y dias trabajados', $hoy, $diasTrabajados]);
//        Log::channel('testing')->info('Log', ['meses trabajados', $mesesTrabajados]);
        if ($mesesTrabajados >= 13 && $this->empleado->acumula_fondos_reserva == 0) {
//            Log::channel('testing')->info('Log', ['entro en el if', $this->calcularSueldo($dias) * NominaService::calcularPorcentajeFondoReserva()]);
            $fondosDeReserva = $this->calcularSueldo($dias) * NominaService::calcularPorcentajeFondoReserva(); // 8.33% del sueldo
//            if ($mesesTrabajados == 12) {
//                $fechaVinculacion = Carbon::createFromFormat('Y-m-d', $this->empleado->fecha_vinculacion)->year($hoy->year);
//                $diasRestantes = 30 - $fechaVinculacion->day + 1;
//                if ($diasRestantes >= 1) {
//                    $fondosDeReserva = $this->calcularSueldo($diasRestantes) * NominaService::calcularPorcentajeFondoReserva(); // 8.33% del sueldo
//                }
//                if ($dias < $diasRestantes) {
//                    $fondosDeReserva = $this->calcularSueldo($dias) * NominaService::calcularPorcentajeFondoReserva(); // 8.33% del sueldo
//                }
//            }
        }
        return floatval(number_format($fondosDeReserva, 2));
    }

    /**
     * Envia un rol de pago a un empleado
     * @throws Exception
     */
    public function enviar_rol_pago(RolPago $rol_pago, Empleado $destinatario)
    {
        try {
            $results = RolPago::empaquetarListado($rol_pago);
            $responsable = Departamento::where('id', 7)->first()->responsable;
            $reportes = ['roles_pago' => $results, 'responsable' => $responsable];
            $vista = 'recursos-humanos.rol_pagos';
            $pdfContent = $this->reporteService->enviar_pdf('A5', 'landscape', $reportes, $vista);
//            Log::channel('testing')->info('Log', ['enviar_rol_pago->args', $reportes, $destinatario]);
            $user = User::where('id', $destinatario->usuario_id)->first();
            Mail::to($user->email)
                ->send(new RolPagoEmail($reportes, $pdfContent, $destinatario, $results[0]['rol_firmado']));
//        } catch (TransportExceptionInterface $mailEx) {
//            // Error específico de envío de correo
//            ExceptionNotificationService::sendExceptionErrorToSystemAdminMail(
//                "Error al enviar correo: " . $mailEx->getMessage() . ". Destinatario: " . ($user->email ?? 'no disponible')
//            );
        } catch (Exception $ex) {
            SystemNotificationService::sendExceptionErrorMailToSystemAdmin(
                "Error general: " . $ex->getMessage() . ". Destinatario: " . ($user->email ?? 'no disponible')
            );
            throw $ex;
        }
    }

    /**
     * @throws Exception|Throwable
     */
//    public function guardarIngresosYEgresos(RolPagoRequest $request, RolPago $rolPago): void
//    {
//        if (!empty($request->ingresos)) {
//            foreach ($request->ingresos as $ingreso) {
//                IngresoRolPago::guardarIngresos($ingreso, $rolPago);
//            }
//        }
//
//        if (!empty($request->egresos)) {
//            foreach ($request->egresos as $egreso) {
//                EgresoRolPago::guardarEgresos($egreso, $rolPago);
//            }
//        }
//    }


}
