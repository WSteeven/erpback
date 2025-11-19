<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\ArchivoSeguimientoTicket
 *
 * @property int $id
 * @property string $nombre
 * @property string $ruta
 * @property int $tamanio_bytes
 * @property int $ticket_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|ArchivoSeguimientoTicket acceptRequest(?array $request = null)
 * @method static Builder|ArchivoSeguimientoTicket filter(?array $request = null)
 * @method static Builder|ArchivoSeguimientoTicket ignoreRequest(?array $request = null)
 * @method static Builder|ArchivoSeguimientoTicket newModelQuery()
 * @method static Builder|ArchivoSeguimientoTicket newQuery()
 * @method static Builder|ArchivoSeguimientoTicket query()
 * @method static Builder|ArchivoSeguimientoTicket setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|ArchivoSeguimientoTicket setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|ArchivoSeguimientoTicket setLoadInjectedDetection($load_default_detection)
 * @method static Builder|ArchivoSeguimientoTicket whereCreatedAt($value)
 * @method static Builder|ArchivoSeguimientoTicket whereId($value)
 * @method static Builder|ArchivoSeguimientoTicket whereNombre($value)
 * @method static Builder|ArchivoSeguimientoTicket whereRuta($value)
 * @method static Builder|ArchivoSeguimientoTicket whereTamanioBytes($value)
 * @method static Builder|ArchivoSeguimientoTicket whereTicketId($value)
 * @method static Builder|ArchivoSeguimientoTicket whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ArchivoSeguimientoTicket extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable;
    protected $table = 'archivos_seguimientos_tickets';
    protected $fillable = ['nombre', 'ruta', 'tamanio_bytes', 'ticket_id'];

    private static array $whiteListFilter = ['*'];
}
