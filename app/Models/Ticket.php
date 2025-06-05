<?php

namespace App\Models;

use App\Models\Tareas\SolicitudAts;
use App\Traits\UppercaseValuesTrait;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Ticket
 *
 * @property int $id
 * @property string $codigo
 * @property string $asunto
 * @property string $descripcion
 * @property string $prioridad
 * @property string|null $fecha_hora_limite
 * @property string $estado
 * @property string|null $observaciones_solicitante
 * @property int|null $calificacion_solicitante
 * @property bool $ticket_interno
 * @property string|null $fecha_hora_asignacion
 * @property string|null $fecha_hora_ejecucion
 * @property string|null $fecha_hora_finalizado
 * @property string|null $fecha_hora_cancelado
 * @property string|null $fecha_hora_calificado
 * @property string|null $motivo_ticket_no_solucionado
 * @property int $solicitante_id
 * @property int|null $responsable_id
 * @property int|null $departamento_responsable_id
 * @property int $tipo_ticket_id
 * @property int|null $motivo_cancelado_ticket_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property bool $ticket_para_mi
 * @property mixed|null $cc
 * @property-read Collection<int, ActividadRealizadaSeguimientoTicket> $actividadesRealizadasSeguimientoTicket
 * @property-read int|null $actividades_realizadas_seguimiento_ticket_count
 * @property-read Collection<int, ArchivoTicket> $archivos
 * @property-read int|null $archivos_count
 * @property-read Collection<int, ArchivoSeguimientoTicket> $archivosSeguimientos
 * @property-read int|null $archivos_seguimientos_count
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Collection<int, CalificacionTicket> $calificacionesTickets
 * @property-read int|null $calificaciones_tickets_count
 * @property-read Departamento|null $departamentoResponsable
 * @property-read MotivoCanceladoTicket|null $motivoCanceladoTicket
 * @property-read Collection<int, Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @property-read Collection<int, PausaTicket> $pausasTicket
 * @property-read int|null $pausas_ticket_count
 * @property-read Empleado|null $responsable
 * @property-read Empleado|null $solicitante
 * @property-read Collection<int, SolicitudAts> $solicitud_ats
 * @property-read int|null $solicitud_ats_count
 * @property-read Collection<int, TicketRechazado> $ticketsRechazados
 * @property-read int|null $tickets_rechazados_count
 * @property-read TipoTicket|null $tipoTicket
 * @method static Builder|Ticket acceptRequest(?array $request = null)
 * @method static Builder|Ticket filter(?array $request = null)
 * @method static Builder|Ticket ignoreRequest(?array $request = null)
 * @method static Builder|Ticket newModelQuery()
 * @method static Builder|Ticket newQuery()
 * @method static Builder|Ticket query()
 * @method static Builder|Ticket setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Ticket setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Ticket setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Ticket whereAsunto($value)
 * @method static Builder|Ticket whereCalificacionSolicitante($value)
 * @method static Builder|Ticket whereCc($value)
 * @method static Builder|Ticket whereCodigo($value)
 * @method static Builder|Ticket whereCreatedAt($value)
 * @method static Builder|Ticket whereDepartamentoResponsableId($value)
 * @method static Builder|Ticket whereDescripcion($value)
 * @method static Builder|Ticket whereEstado($value)
 * @method static Builder|Ticket whereFechaHoraAsignacion($value)
 * @method static Builder|Ticket whereFechaHoraCalificado($value)
 * @method static Builder|Ticket whereFechaHoraCancelado($value)
 * @method static Builder|Ticket whereFechaHoraEjecucion($value)
 * @method static Builder|Ticket whereFechaHoraFinalizado($value)
 * @method static Builder|Ticket whereFechaHoraLimite($value)
 * @method static Builder|Ticket whereId($value)
 * @method static Builder|Ticket whereMotivoCanceladoTicketId($value)
 * @method static Builder|Ticket whereMotivoTicketNoSolucionado($value)
 * @method static Builder|Ticket whereObservacionesSolicitante($value)
 * @method static Builder|Ticket wherePrioridad($value)
 * @method static Builder|Ticket whereResponsableId($value)
 * @method static Builder|Ticket whereSolicitanteId($value)
 * @method static Builder|Ticket whereTicketInterno($value)
 * @method static Builder|Ticket whereTicketParaMi($value)
 * @method static Builder|Ticket whereTipoTicketId($value)
 * @method static Builder|Ticket whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Ticket extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable, UppercaseValuesTrait, Searchable;

    // Estados de un ticket
    const RECHAZADO = 'RECHAZADO';
    const ASIGNADO = 'ASIGNADO';
    const REASIGNADO = 'REASIGNADO';
    const EJECUTANDO = 'EJECUTANDO';
    const PAUSADO = 'PAUSADO';
    const CANCELADO = 'CANCELADO';
    const FINALIZADO_SIN_SOLUCION = 'FINALIZADO SIN SOLUCIÓN';
    const FINALIZADO_SOLUCIONADO = 'FINALIZADO SOLUCIONADO';
    const CALIFICADO = 'CALIFICADO';
    const ETIQUETADOS_A_MI = 'ETIQUETADOS_A_MI';
    const RECURRENTE = 'RECURRENTE';

    // Prioridad
    const ALTA = 'ALTA';
    const MEDIA = 'MEDIA';
    const BAJA = 'BAJA';
    const EMERGENCIA = 'EMERGENCIA';

    // Configuracion ATS de tickets
    const TIPO_TICKET_ATS = 166;
    const SSO = 5;

    // Motivos de pausas
    const PAUSA_AUTOMATICA_SISTEMA = 14;

    protected $table = 'tickets';
    protected $fillable = [
        // 'codigo',
        'asunto',
        'descripcion',
        'prioridad',
        'fecha_hora_limite',
        'estado',
        'observaciones_solicitante',
        'calificacion_solicitante',
        'fecha_hora_asignacion',
        'fecha_hora_ejecucion',
        'fecha_hora_finalizado',
        'fecha_hora_cancelado',
        'fecha_hora_calificado',
        'motivo_ticket_no_solucionado',
        'ticket_interno',
        'ticket_para_mi',
        'cc',
        'solicitante_id',
        'responsable_id',
        'departamento_responsable_id',
        'tipo_ticket_id',
        'motivo_cancelado_ticket_id',
        // recurrente
        'is_recurring',
        'recurrence_active',
        'recurrence_frequency',
        'recurrence_time',
        'recurrence_day_of_week',
        'recurrence_day_of_month',
    ];

    protected $casts = ['ticket_interno' => 'boolean', 'ticket_para_mi' => 'boolean'];

    private static array $whiteListFilter = ['*'];

    private array $aliasListFilter = [
        'responsable.departamento.id' => 'departamento_id',
    ];

    public function toSearchableArray()
    {
        return [ //
            'id' => $this->id,
            'codigo_ticket' => 'TCKT-' . $this->id,
            'asunto' => $this->asunto,
            // 'descripcion' => Str::limit($this->descripcion, 800, ''),
            // 'observaciones_solicitante' => $this->observaciones_solicitante,
            'fecha_hora_asignacion' => (string) $this->fecha_hora_asignacion,
            // 'motivo_ticket_no_solucionado' => $this->motivo_ticket_no_solucionado,
            'solicitante' => Empleado::extraerApellidosNombres($this->solicitante),
            'responsable' => Empleado::extraerApellidosNombres($this->responsable),
            'solicitante_id' => $this->solicitante_id,
            'responsable_id' => $this->responsable_id,
            'departamento_responsable' => $this->departamentoResponsable?->nombre,
            'tipo_ticket' => $this->tipoTicket->nombre,
            // 'motivo_cancelado_ticket' => $this->motivoCanceladoTicket?->motivo,
            'estado' => $this->estado,
        ];
    }

    /*************
     * Relaciones
     *************/
    public function solicitante()
    {
        return $this->belongsTo(Empleado::class, 'solicitante_id', 'id');
    }

    public function responsable()
    {
        return $this->belongsTo(Empleado::class, 'responsable_id', 'id');
    }

    public function departamentoResponsable()
    {
        return $this->belongsTo(Departamento::class, 'departamento_responsable_id', 'id');
    }

    public function tipoTicket()
    {
        return $this->belongsTo(TipoTicket::class, 'tipo_ticket_id', 'id');
    }

    // Archivos al crear un ticket
    public function archivos()
    {
        return $this->hasMany(ArchivoTicket::class);
    }

    // Archivos al registrar el seguimiento del ticket
    public function archivosSeguimientos()
    {
        return $this->hasMany(ArchivoSeguimientoTicket::class);
    }

    public function pausasTicket()
    {
        return $this->hasMany(PausaTicket::class);
    }

    public function ticketsRechazados()
    {
        return $this->hasMany(TicketRechazado::class);
    }

    public function calificacionesTickets()
    {
        return $this->hasMany(CalificacionTicket::class);
    }

    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }

    public function actividadesRealizadasSeguimientoTicket()
    {
        return $this->hasMany(ActividadRealizadaSeguimientoTicket::class);
    }

    public function motivoCanceladoTicket()
    {
        return $this->belongsTo(MotivoCanceladoTicket::class);
    }

    public function solicitud_ats()
    {
        return $this->hasMany(SolicitudAts::class);
    }
}
