<?php

namespace App\Models\Tickets;

use App\Models\Empleado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Tickets\ComentarioTicket
 *
 * @property int $id
 * @property string $comentario
 * @property int $empleado_id
 * @property int|null $ticket_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $empleado
 * @method static \Illuminate\Database\Eloquent\Builder|ComentarioTicket acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ComentarioTicket filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ComentarioTicket ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ComentarioTicket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ComentarioTicket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ComentarioTicket query()
 * @method static \Illuminate\Database\Eloquent\Builder|ComentarioTicket setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ComentarioTicket setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ComentarioTicket setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|ComentarioTicket whereComentario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComentarioTicket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComentarioTicket whereEmpleadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComentarioTicket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComentarioTicket whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComentarioTicket whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ComentarioTicket extends Model implements Auditable
{
    use HasFactory, Filterable, AuditableModel;

    protected $table = 'tckt_comentarios_tickets';
    protected $fillable = [
        'comentario',
        'empleado_id',
        'ticket_id',
    ];
    private static $whiteListFilter = ['*'];

    /**************
     * Relaciones
     **************/
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
