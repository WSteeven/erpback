<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;


use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\RecursosHumanos\NominaPrestamos\HorasExtraTipo
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|HorasExtraTipo acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|HorasExtraTipo filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|HorasExtraTipo ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|HorasExtraTipo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HorasExtraTipo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HorasExtraTipo query()
 * @method static \Illuminate\Database\Eloquent\Builder|HorasExtraTipo setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|HorasExtraTipo setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|HorasExtraTipo setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|HorasExtraTipo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HorasExtraTipo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HorasExtraTipo whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HorasExtraTipo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class HorasExtraTipo extends Model  implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'horas_extra_tipos';
    protected $fillable = [
        'nombre'
    ];

    private static $whiteListFilter = [
        'id',
        'nombre',
    ];
}
