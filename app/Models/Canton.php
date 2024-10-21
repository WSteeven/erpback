<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Canton
 *
 * @method static where(string $string, mixed $ciudad)
 * @property int $id
 * @property string $canton
 * @property string|null $cod_canton
 * @property int $provincia_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Parroquia> $parroquias
 * @property-read int|null $parroquias_count
 * @property-read \App\Models\Provincia $provincia
 * @method static \Illuminate\Database\Eloquent\Builder|Canton acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Canton filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Canton ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Canton newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Canton newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Canton query()
 * @method static \Illuminate\Database\Eloquent\Builder|Canton setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Canton setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Canton setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Canton whereCanton($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Canton whereCodCanton($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Canton whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Canton whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Canton whereProvinciaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Canton whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Canton extends Model implements Auditable
{
    use HasFactory, AuditableModel;
    use UppercaseValuesTrait, Filterable;

    protected $table = "cantones";
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static $whiteListFilter = ['*'];

    /**
     * Get the parroquia associated with the canton.
     */
    public function parroquias()
    {
        return $this->hasMany(Parroquia::class);
    }

    /*
    * Get the provincia that owns the canton
    */
    public function provincia()
    {
        return $this->belongsTo(Provincia::class);
    }
}
