<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
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
 * App\Models\Canton
 *
 * @method static where(string $string, mixed $ciudad)
 * @property int $id
 * @property string $canton
 * @property string|null $cod_canton
 * @property int $provincia_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Collection<int, Parroquia> $parroquias
 * @property-read int|null $parroquias_count
 * @property-read Provincia $provincia
 * @method static Builder|Canton acceptRequest(?array $request = null)
 * @method static Builder|Canton filter(?array $request = null)
 * @method static Builder|Canton ignoreRequest(?array $request = null)
 * @method static Builder|Canton newModelQuery()
 * @method static Builder|Canton newQuery()
 * @method static Builder|Canton query()
 * @method static Builder|Canton setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Canton setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Canton setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Canton whereCanton($value)
 * @method static Builder|Canton whereCodCanton($value)
 * @method static Builder|Canton whereCreatedAt($value)
 * @method static Builder|Canton whereId($value)
 * @method static Builder|Canton whereProvinciaId($value)
 * @method static Builder|Canton whereUpdatedAt($value)
 * @mixin Eloquent
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

    private static array $whiteListFilter = ['*'];

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
