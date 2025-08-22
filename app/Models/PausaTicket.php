<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\PausaTicket
 *
 * @property int $id
 * @property string $fecha_hora_pausa
 * @property string|null $fecha_hora_retorno
 * @property int $motivo_pausa_ticket_id
 * @property int $ticket_id
 * @property int|null $responsable_id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read MotivoPausaTicket|null $motivoPausaTicket
 * @property-read Empleado|null $responsable
 * @method static Builder|PausaTicket newModelQuery()
 * @method static Builder|PausaTicket newQuery()
 * @method static Builder|PausaTicket query()
 * @method static Builder|PausaTicket whereCreatedAt($value)
 * @method static Builder|PausaTicket whereFechaHoraPausa($value)
 * @method static Builder|PausaTicket whereFechaHoraRetorno($value)
 * @method static Builder|PausaTicket whereId($value)
 * @method static Builder|PausaTicket whereMotivoPausaTicketId($value)
 * @method static Builder|PausaTicket whereResponsableId($value)
 * @method static Builder|PausaTicket whereTicketId($value)
 * @method static Builder|PausaTicket whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PausaTicket extends Model implements Auditable
{
    use HasFactory, AuditableModel;
    public $timestamps = false;
    protected $table = "pausas_tickets";

    protected $fillable = [
        'fecha_hora_pausa',
        'fecha_hora_retorno',
        'motivo_pausa_ticket_id',
        'ticket_id',
        'responsable_id',
    ];

    public function motivoPausaTicket()
    {
        return $this->belongsTo(MotivoPausaTicket::class);
    }

    public function responsable()
    {
        return $this->belongsTo(Empleado::class, 'responsable_id', 'id');
    }
}
