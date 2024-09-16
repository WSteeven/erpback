<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\ArchivoTicket
 *
 * @property int $id
 * @property string $nombre
 * @property string $ruta
 * @property int $tamanio_bytes
 * @property int $ticket_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoTicket acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoTicket filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoTicket ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoTicket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoTicket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoTicket query()
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoTicket setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoTicket setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoTicket setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoTicket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoTicket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoTicket whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoTicket whereRuta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoTicket whereTamanioBytes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoTicket whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoTicket whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ArchivoTicket extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable;
    protected $table = 'archivos_tickets';
    protected $fillable = ['nombre', 'ruta', 'tamanio_bytes', 'ticket_id'];

    private static $whiteListFilter = ['*'];
}
