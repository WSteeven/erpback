<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\CalificacionTicket
 *
 * @property int $id
 * @property string $solicitante_o_responsable
 * @property string $observacion
 * @property int $calificacion
 * @property int $calificador_id
 * @property int $ticket_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Empleado $calificador
 * @method static \Illuminate\Database\Eloquent\Builder|CalificacionTicket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CalificacionTicket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CalificacionTicket query()
 * @method static \Illuminate\Database\Eloquent\Builder|CalificacionTicket whereCalificacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalificacionTicket whereCalificadorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalificacionTicket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalificacionTicket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalificacionTicket whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalificacionTicket whereSolicitanteOResponsable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalificacionTicket whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalificacionTicket whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CalificacionTicket extends Model implements Auditable
{
    use HasFactory, AuditableModel;

    const SOLICITANTE = 'SOLICITANTE';
    const RESPONSABLE = 'RESPONSABLE';

    protected $table = "calificaciones_tickets";

    protected $fillable = [
        'solicitante_o_responsable',
        'observacion',
        'calificacion',
        'calificador_id',
        'ticket_id',
    ];

    public function calificador()
    {
        return $this->belongsTo(Empleado::class, 'calificador_id', 'id');
    }
}
