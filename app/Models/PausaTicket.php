<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\MotivoPausaTicket|null $motivoPausaTicket
 * @property-read \App\Models\Empleado|null $responsable
 * @method static \Illuminate\Database\Eloquent\Builder|PausaTicket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PausaTicket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PausaTicket query()
 * @method static \Illuminate\Database\Eloquent\Builder|PausaTicket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PausaTicket whereFechaHoraPausa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PausaTicket whereFechaHoraRetorno($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PausaTicket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PausaTicket whereMotivoPausaTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PausaTicket whereResponsableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PausaTicket whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PausaTicket whereUpdatedAt($value)
 * @mixin \Eloquent
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
