<?php

namespace Src\App\RecursosHumanos\ControlPersonal;

use App\Http\Controllers\FondosRotativos\Saldo\SaldoController;
use App\Mail\RecursosHumanos\EmpleadoDesvinculadoMail;
use App\Models\Autorizacion;
use App\Models\ComprasProveedores\OrdenCompra;
use App\Models\ConfiguracionGeneral;
use App\Models\Departamento;
use App\Models\Empleado;
use App\Models\EstadoTransaccion;
use App\Models\FondosRotativos\Gasto\EstadoViatico;
use App\Models\FondosRotativos\Gasto\Gasto;
use App\Models\FondosRotativos\Saldo\Saldo;
use App\Models\FondosRotativos\Saldo\Transferencias;
use App\Models\RecursosHumanos\NominaPrestamos\CuotaDescuento;
use App\Models\RecursosHumanos\NominaPrestamos\Descuento;
use App\Models\RecursosHumanos\NominaPrestamos\PlazoPrestamoEmpresarial;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoEmpresarial;
use App\Models\Subtarea;
use App\Models\Tarea;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Vehiculos\Vehiculo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Mail;
use Src\Config\EstadosTransacciones;

class DesvinculacionEmpleadoService
{
    private Empleado $empleado;

    public function __construct()
    {
        $this->empleado = new Empleado();
    }

    public function setEmpleado(Empleado $empleado)
    {
        $this->empleado = $empleado;
    }

    public function obtenerPrestamosActivosEmpleado()
    {
        $saldo = 0;
        $cuotasPendientes = 0;
        $prestamosActivos = PrestamoEmpresarial::where('solicitante', $this->empleado->id)->where('estado', true)->get();
        $cantidadPrestamos = $prestamosActivos->count();

        foreach ($prestamosActivos as $prestamoEmpresarial) {
            $plazos = PlazoPrestamoEmpresarial::where('id_prestamo_empresarial', $prestamoEmpresarial->id)
                ->where('pago_cuota', false)
                ->get();
            $cuotasPendientes += $plazos->count();
            $saldo += $plazos->sum('valor_cuota');
        }
        return ['cantidad_prestamos' => $cantidadPrestamos, 'saldo_acumulado' => $saldo, 'cuotas_pendientes' => $cuotasPendientes];
    }

    public function obtenerDescuentosActivosEmpleado()
    {
        $saldo = 0;
        $cuotasPendientes = 0;
        $descuentosActivos = Descuento::where('empleado_id', $this->empleado->id)->where('pagado', false)->get();
        $cantidadDescuentos = $descuentosActivos->count();

        foreach ($descuentosActivos as $descuento) {
            $cuotas = CuotaDescuento::where('descuento_id', $descuento->id)
                ->where('pagada', false)
                ->get();
            $cuotasPendientes += $cuotas->count();
            $saldo += $cuotas->sum('valor_cuota');
        }
        return ['cantidad_descuentos' => $cantidadDescuentos, 'saldo_acumulado' => $saldo, 'cuotas_pendientes' => $cuotasPendientes];
    }

    /**
     * Devuelve los gastos pendientes por aprobarle a alguien más cuando tipo es 'mi aprobacion'
     * o los gastos pendientes por aprobar del empleado cuando tipo es 'mis gastos'
     * @param string $tipo
     * @return Gasto[]|Builder[]|Collection
     */
    public function obtenerGastosPendientes(string $tipo = 'mis gastos')
    {
        $query = Gasto::where('estado', EstadoViatico::POR_APROBAR_ID);
        return match ($tipo) {
            'mi aprobacion' => $query->where('aut_especial', $this->empleado->id)->get(),
            default => $query->where('id_usuario', $this->empleado->id)->get()
        };
    }

    public function obtenerOrdenesCompraPendientes(string $tipo = 'pendientes autorizacion')
    {
        $query = OrdenCompra::where('solicitante_id', $this->empleado->id);

        return match ($tipo) {
            'autorizadas pendientes revision compras' => $query->where('autorizacion_id', Autorizacion::APROBADO_ID)->where('revisada_compras', false)->where('estado_id', EstadosTransacciones::PENDIENTE)->get(),
            'autorizadas pendientes realizar' => $query->where('autorizacion_id', Autorizacion::APROBADO_ID)->where('realizada', false)->whereNot('estado_id', EstadosTransacciones::PENDIENTE)->get(),
            default => $query->where('autorizacion_id', Autorizacion::PENDIENTE_ID)->get(),
        };
    }

    public function obtenerTicketsEmpleado()
    {
        $query = Ticket::where('responsable_id', $this->empleado->id);
        $ticketsAsignados = $query->where('estado', Ticket::ASIGNADO)->get();
        $ticketsReasignados = $query->where('estado', Ticket::REASIGNADO)->get();
        $ticketEjecutando = $query->where('estado', Ticket::EJECUTANDO)->get();
        $ticketsPausados = $query->where('estado', Ticket::PAUSADO)->get();
        $ticketsSolicitadosEmpleado = Ticket::where('solicitante_id', $this->empleado->id)->whereIn('estado', [Ticket::ASIGNADO, Ticket::REASIGNADO, Ticket::EJECUTANDO, Ticket::PAUSADO])->get();
        return [
            'tickets_asignados' => $ticketsAsignados,
            'tickets_reasignados' => $ticketsReasignados,
            'ticket_ejecutando' => $ticketEjecutando,
            'tickets_pausados' => $ticketsPausados,
            'tickets_solicitados_empleado' => $ticketsSolicitadosEmpleado,
        ];
    }

    public function obtenerTareasPendientesEmpleado()
    {
        $subtareas = Subtarea::where(function ($q) {
            $q->where('empleado_id', $this->empleado->id)
                ->orWhere('grupo_id', $this->empleado->grupo_id)
                ->orWhereJsonContains('empleados_designados', $this->empleado->id)
                ->get();
        })->whereIn('estado', [Subtarea::EJECUTANDO, Subtarea::ASIGNADO, Subtarea::PAUSADO, Subtarea::AGENDADO])->get();

        $tareas = Tarea::whereIn('id', $subtareas->pluck('id'))->where('finalizado', false)->get();

        return ['tareas_pendientes' => $tareas, 'subtareas_pendientes' => $subtareas,
            'subtareas_asignadas' => $subtareas->where('estado', Subtarea::ASIGNADO),
            'subtareas_ejecutando' => $subtareas->where('estado', Subtarea::EJECUTANDO),
            'subtareas_pausadas' => $subtareas->where('estado', Subtarea::PAUSADO),
            'subtareas_agendadas' => $subtareas->where('estado', Subtarea::AGENDADO)
        ];
    }

    public function obtenerTareasPendientesCreadasEmpleado()
    {
        return Tarea::where(function ($q) {
            $q->where('coordinador_id', $this->empleado->id)
                ->orWhere('fiscalizador_id', $this->empleado->id);
        })->where('finalizado', false)->get();
    }

    public function obtenerVehiculosAsignadosEmpleado()
    {
        return Vehiculo::where('custodio_id', $this->empleado->id)->where('estado', true)->get();
    }

    public function obtenerSaldoFondosRotativosEmpleado()
    {
        return Saldo::where('empleado_id', $this->empleado->id)->orderBy('updated_at', 'desc')->first()?->saldo_actual ?? 0;
    }

    public function obtenerTransferenciasPendientes(string $tipo = 'enviadas')
    {
        $query = Transferencias::where('estado', Transferencias::PENDIENTE);
        return match ($tipo) {
            'recibidas' => $query->where('usuario_recibe_id', $this->empleado->id)->get(),
            default => Transferencias::where('usuario_envia_id', $this->empleado->id)->get()
        };
    }

    public function departamentoResponsable()
    {
        return Departamento::where('responsable_id', $this->empleado->id)->get();
    }

    public function obtenerGrupoEmpleadoLidera()
    {
        if (is_null($this->empleado->grupo_id)) return null;

        return ['es_lider_grupo' => $this->empleado->user->hasRole(User::ROL_LIDER_DE_GRUPO), 'grupo' => $this->empleado->grupo];
    }

    public function desvincularEmpleado($fecha_salida, $motivo_desvinculacion, $resumenSalida)
    {

        // Se envía un correo de notificación con los detalles de la desvinculación a RRHH
        $configuracion = ConfiguracionGeneral::first();
        $pdf = Pdf::loadView('recursos-humanos.desvinculacion_empleado', [
            'resumen' => $resumenSalida,
            'configuracion' => $configuracion,
            'empleado' => $this->empleado,
            'motivo' => $motivo_desvinculacion
        ]);
        $pdf->render();
        $departamentoRRHH = Departamento::where('nombre', Departamento::DEPARTAMENTO_RRHH)->first();
        $responsableRRHH = Empleado::find($departamentoRRHH->responsable_id);
        Mail::to($responsableRRHH->user->email)->send(new EmpleadoDesvinculadoMail($this->empleado, $resumenSalida, $configuracion, $pdf->output()));


        // Se desvincula guardando la fecha de salida, el motivo de desvinculación
        /*
        $this->empleado->update([
            'fecha_salida' => $fecha_salida,
            'motivo_desvinculacion' => $motivo_desvinculacion,
            'estado' => false,
        ]);
*/

    }
}
