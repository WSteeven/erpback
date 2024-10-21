<?php

namespace App\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Archivo
 *
 * @property int $id
 * @property string $nombre
 * @property string $ruta
 * @property int $tamanio_bytes
 * @property int $archivable_id
 * @property string $archivable_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $tipo
 * @property-read Model|\Eloquent $archivable
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|Archivo acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Archivo filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Archivo ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Archivo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Archivo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Archivo query()
 * @method static \Illuminate\Database\Eloquent\Builder|Archivo setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Archivo setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Archivo setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Archivo whereArchivableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archivo whereArchivableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archivo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archivo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archivo whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archivo whereRuta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archivo whereTamanioBytes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archivo whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Archivo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Archivo extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable;
    protected $table = 'archivos';
    protected $fillable = ['nombre', 'ruta', 'tamanio_bytes', 'archivable_id', 'archivable_type', 'tipo'];

    private static $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

    // Relación polimorfica
    public function archivable()
    {
        return $this->morphTo();
    }

    /**
     * ______________________________________________________________________________________
     * FUNCIONES
     * ______________________________________________________________________________________
     */
    
}
