<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;

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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $responsable
 * @method static Builder|ActividadRealizadaSeguimientoTicket acceptRequest(?array $request = null)
 * @method static Builder|ActividadRealizadaSeguimientoTicket filter(?array $request = null)
 * @method static Builder|ActividadRealizadaSeguimientoTicket ignoreRequest(?array $request = null)
 * @method static Builder|ActividadRealizadaSeguimientoTicket newModelQuery()
 * @method static Builder|ActividadRealizadaSeguimientoTicket newQuery()
 * @method static Builder|ActividadRealizadaSeguimientoTicket query()
 * @method static Builder|ActividadRealizadaSeguimientoTicket setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|ActividadRealizadaSeguimientoTicket setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|ActividadRealizadaSeguimientoTicket setLoadInjectedDetection($load_default_detection)
 * @method static Builder|ActividadRealizadaSeguimientoTicket whereActividadRealizada($value)
 * @method static Builder|ActividadRealizadaSeguimientoTicket whereCreatedAt($value)
 * @method static Builder|ActividadRealizadaSeguimientoTicket whereFechaHora($value)
 * @method static Builder|ActividadRealizadaSeguimientoTicket whereFotografia($value)
 * @method static Builder|ActividadRealizadaSeguimientoTicket whereId($value)
 * @method static Builder|ActividadRealizadaSeguimientoTicket whereObservacion($value)
 * @method static Builder|ActividadRealizadaSeguimientoTicket whereResponsableId($value)
 * @method static Builder|ActividadRealizadaSeguimientoTicket whereTicketId($value)
 * @method static Builder|ActividadRealizadaSeguimientoTicket whereUpdatedAt($value)
 * @mixin Eloquent
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

    private static array $whiteListFilter = [
        '*',
    ];

    public function responsable()
    {
        return $this->belongsTo(Empleado::class, 'responsable_id', 'id');
    }
}
