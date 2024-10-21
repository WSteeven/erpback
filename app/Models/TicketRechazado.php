<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;

/**
 * App\Models\TicketRechazado
 *
 * @property int $id
 * @property string|null $fecha_hora
 * @property string $motivo
 * @property int $responsable_id
 * @property int $ticket_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Empleado|null $responsable
 * @method static \Illuminate\Database\Eloquent\Builder|TicketRechazado newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketRechazado newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketRechazado query()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketRechazado whereFechaHora($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketRechazado whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketRechazado whereMotivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketRechazado whereResponsableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketRechazado whereTicketId($value)
 * @mixin \Eloquent
 */
class TicketRechazado extends Model implements Auditable
{
    use HasFactory, AuditableModel, UppercaseValuesTrait;
    public $timestamps = false;
    protected $table = "tickets_rechazados";

    protected $fillable = [
        'fecha_hora',
        'motivo',
        'responsable_id',
        'ticket_id',
    ];

    public function responsable()
    {
        return $this->belongsTo(Empleado::class, 'responsable_id', 'id');
    }
}
