<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Ticket extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable, UppercaseValuesTrait;

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

    // Prioridad
    const ALTA = 'ALTA';
    const MEDIA = 'MEDIA';
    const BAJA = 'BAJA';
    const EMERGENCIA = 'EMERGENCIA';

    protected $table = 'tickets';
    protected $fillable = [
        'codigo',
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
        'solicitante_id',
        'responsable_id',
        'departamento_responsable_id',
        'tipo_ticket_id',
        'motivo_cancelado_ticket_id',
    ];

    protected $casts = ['ticket_interno' => 'boolean', 'ticket_para_mi' => 'boolean'];

    private static $whiteListFilter = ['*'];

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
}
