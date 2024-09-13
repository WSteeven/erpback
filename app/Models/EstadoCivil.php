<?php

namespace App\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\EstadoCivil
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoCivil acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoCivil filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoCivil ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoCivil newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoCivil newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoCivil query()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoCivil setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoCivil setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoCivil setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoCivil whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoCivil whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoCivil whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoCivil whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EstadoCivil extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'estado_civil';
    protected $fillable = [
        'nombre'
    ];

    private static $whiteListFilter = [
        'id',
        'nombre',
    ];
}
