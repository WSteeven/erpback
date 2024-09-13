<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\RecursosHumanos\NominaPrestamos\HorasExtraSubTipo
 *
 * @property int $id
 * @property string $nombre
 * @property int $hora_extra_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\RecursosHumanos\NominaPrestamos\HorasExtraTipo|null $horas_extras_info
 * @method static \Illuminate\Database\Eloquent\Builder|HorasExtraSubTipo acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|HorasExtraSubTipo filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|HorasExtraSubTipo ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|HorasExtraSubTipo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HorasExtraSubTipo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HorasExtraSubTipo query()
 * @method static \Illuminate\Database\Eloquent\Builder|HorasExtraSubTipo setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|HorasExtraSubTipo setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|HorasExtraSubTipo setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|HorasExtraSubTipo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HorasExtraSubTipo whereHoraExtraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HorasExtraSubTipo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HorasExtraSubTipo whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HorasExtraSubTipo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class HorasExtraSubTipo extends Model  implements Auditable
{
    use HasFactory;
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'horas_extra_sub_tipos';
    protected $fillable = [
        'nombre','hora_extra_id'
    ];

    private static $whiteListFilter = [
        'id',
        'nombre',
        'hora_extra'
    ];
    public function horas_extras_info()
    {
        return $this->hasOne(HorasExtraTipo::class, 'id', 'hora_extra_id');
    }
}
