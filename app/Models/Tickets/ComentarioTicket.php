<?php

namespace App\Models\Tickets;

use App\Models\Archivo;
use App\Models\Empleado;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Tickets\ComentarioTicket
 *
 * @property int $id
 * @property string $comentario
 * @property int $empleado_id
 * @property int|null $ticket_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $empleado
 * @method static Builder|ComentarioTicket acceptRequest(?array $request = null)
 * @method static Builder|ComentarioTicket filter(?array $request = null)
 * @method static Builder|ComentarioTicket ignoreRequest(?array $request = null)
 * @method static Builder|ComentarioTicket newModelQuery()
 * @method static Builder|ComentarioTicket newQuery()
 * @method static Builder|ComentarioTicket query()
 * @method static Builder|ComentarioTicket setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|ComentarioTicket setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|ComentarioTicket setLoadInjectedDetection($load_default_detection)
 * @method static Builder|ComentarioTicket whereComentario($value)
 * @method static Builder|ComentarioTicket whereCreatedAt($value)
 * @method static Builder|ComentarioTicket whereEmpleadoId($value)
 * @method static Builder|ComentarioTicket whereId($value)
 * @method static Builder|ComentarioTicket whereTicketId($value)
 * @method static Builder|ComentarioTicket whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ComentarioTicket extends Model implements Auditable
{
    use HasFactory, Filterable, AuditableModel;

    protected $table = 'tckt_comentarios_tickets';
    protected $fillable = [
        'comentario',
        'empleado_id',
        'ticket_id',
        'adjuntos'
    ];

    protected $casts = [
        'adjuntos' => 'array',
    ];
    private static array $whiteListFilter = ['*'];

    const ADJUNTO_COMENTARIO_TICKET = 'ADJUNTO COMENTARIO';
    /**************
     * Relaciones
     **************/
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }

}
