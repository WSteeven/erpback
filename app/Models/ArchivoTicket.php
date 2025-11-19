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
 * App\Models\ArchivoTicket
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
 * @method static Builder|ArchivoTicket acceptRequest(?array $request = null)
 * @method static Builder|ArchivoTicket filter(?array $request = null)
 * @method static Builder|ArchivoTicket ignoreRequest(?array $request = null)
 * @method static Builder|ArchivoTicket newModelQuery()
 * @method static Builder|ArchivoTicket newQuery()
 * @method static Builder|ArchivoTicket query()
 * @method static Builder|ArchivoTicket setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|ArchivoTicket setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|ArchivoTicket setLoadInjectedDetection($load_default_detection)
 * @method static Builder|ArchivoTicket whereCreatedAt($value)
 * @method static Builder|ArchivoTicket whereId($value)
 * @method static Builder|ArchivoTicket whereNombre($value)
 * @method static Builder|ArchivoTicket whereRuta($value)
 * @method static Builder|ArchivoTicket whereTamanioBytes($value)
 * @method static Builder|ArchivoTicket whereTicketId($value)
 * @method static Builder|ArchivoTicket whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ArchivoTicket extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable;
    protected $table = 'archivos_tickets';
    protected $fillable = ['nombre', 'ruta', 'tamanio_bytes', 'ticket_id'];

    private static array $whiteListFilter = ['*'];
}
