<?php

namespace App\Models\Tareas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Tareas\SolicitudAts
 *
 * @property int $id
 * @property int|null $ticket_id
 * @property int|null $subtarea_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudAts newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudAts newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudAts query()
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudAts whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudAts whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudAts whereSubtareaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudAts whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudAts whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SolicitudAts extends Model implements Auditable
{
    use HasFactory, AuditableModel;

    protected $table = 'tar_solicitudes_ats';
    protected $fillable = [
        'ticket_id',
        'subtarea_id',
    ];
}
