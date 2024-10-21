<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\RecursosHumanos\NominaPrestamos\Periodo
 *
 * @property int $id
 * @property string $nombre
 * @property int $activo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|Periodo acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Periodo filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Periodo ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Periodo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Periodo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Periodo query()
 * @method static \Illuminate\Database\Eloquent\Builder|Periodo setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Periodo setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Periodo setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Periodo whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Periodo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Periodo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Periodo whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Periodo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Periodo extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'periodos';
    protected $fillable = [
        'nombre',
        'activo'
    ];

    private static $whiteListFilter = [
        'id',
        'nombre',
        'activo'
    ];
}
