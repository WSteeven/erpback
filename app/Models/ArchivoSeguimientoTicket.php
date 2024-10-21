<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\ArchivoSeguimientoTicket
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
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimientoTicket acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimientoTicket filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimientoTicket ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimientoTicket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimientoTicket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimientoTicket query()
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimientoTicket setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimientoTicket setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimientoTicket setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimientoTicket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimientoTicket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimientoTicket whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimientoTicket whereRuta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimientoTicket whereTamanioBytes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimientoTicket whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivoSeguimientoTicket whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ArchivoSeguimientoTicket extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable;
    protected $table = 'archivos_seguimientos_tickets';
    protected $fillable = ['nombre', 'ruta', 'tamanio_bytes', 'ticket_id'];

    private static $whiteListFilter = ['*'];
}
