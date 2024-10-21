<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Provincia
 *
 * @property int $id
 * @property string $provincia
 * @property string|null $cod_provincia
 * @property int|null $pais_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Canton> $cantones
 * @property-read int|null $cantones_count
 * @property-read \App\Models\Pais|null $pais
 * @method static \Illuminate\Database\Eloquent\Builder|Provincia acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Provincia filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Provincia ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Provincia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Provincia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Provincia query()
 * @method static \Illuminate\Database\Eloquent\Builder|Provincia setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Provincia setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Provincia setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Provincia whereCodProvincia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provincia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provincia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provincia wherePaisId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provincia whereProvincia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Provincia whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Provincia extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;

    protected $table = "provincias";
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];
    private static $whiteListFilter = ['*'];



    /**
     * Get the cantones of the provincia.
     */
    public function cantones()
    {
        return $this->hasMany(Canton::class);
    }

    /**
     * Get the country than owns the province.
     */
    public  function pais()
    {
        return $this->belongsTo(Pais::class);
    }
}
