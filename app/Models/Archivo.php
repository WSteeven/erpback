<?php

namespace App\Models;

use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Archivo
 *
 * @property int $id
 * @property string $nombre
 * @property string $ruta
 * @property int $tamanio_bytes
 * @property int $archivable_id
 * @property string $archivable_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $tipo
 * @property-read Model|Eloquent $archivable
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|Archivo acceptRequest(?array $request = null)
 * @method static Builder|Archivo filter(?array $request = null)
 * @method static Builder|Archivo ignoreRequest(?array $request = null)
 * @method static Builder|Archivo newModelQuery()
 * @method static Builder|Archivo newQuery()
 * @method static Builder|Archivo query()
 * @method static Builder|Archivo setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Archivo setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Archivo setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Archivo whereArchivableId($value)
 * @method static Builder|Archivo whereArchivableType($value)
 * @method static Builder|Archivo whereCreatedAt($value)
 * @method static Builder|Archivo whereId($value)
 * @method static Builder|Archivo whereNombre($value)
 * @method static Builder|Archivo whereRuta($value)
 * @method static Builder|Archivo whereTamanioBytes($value)
 * @method static Builder|Archivo whereTipo($value)
 * @method static Builder|Archivo whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Archivo extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable;
    protected $table = 'archivos';
    protected $fillable = ['nombre', 'ruta', 'tamanio_bytes', 'archivable_id', 'archivable_type', 'tipo'];

    private static array $whiteListFilter = ['*'];

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
