<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Support\Facades\Log;

/**
 * App\Models\ActividadRealizadaSeguimientoTicket
 *
 * @property int $id
 * @property string $fecha_hora
 * @property string $actividad_realizada
 * @property string|null $observacion
 * @property string|null $fotografia
 * @property int $ticket_id
 * @property int|null $responsable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Empleado|null $responsable
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoTicket acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoTicket filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoTicket ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoTicket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoTicket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoTicket query()
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoTicket setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoTicket setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoTicket setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoTicket whereActividadRealizada($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoTicket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoTicket whereFechaHora($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoTicket whereFotografia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoTicket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoTicket whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoTicket whereResponsableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoTicket whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadRealizadaSeguimientoTicket whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ActividadRealizadaSeguimientoTicket extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'actividades_realizadas_seguimientos_tickets';
    protected $fillable = ['fecha_hora', 'actividad_realizada', 'observacion', 'fotografia', 'ticket_id', 'responsable_id'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static $whiteListFilter = [
        '*',
    ];

    public function responsable()
    {
        return $this->belongsTo(Empleado::class, 'responsable_id', 'id');
    }
}
