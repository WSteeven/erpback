<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Parroquia
 *
 * @property int $id
 * @property string $parroquia
 * @property string|null $cod_parroquia
 * @property string|null $cod_postal
 * @property int $canton_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Canton|null $canton
 * @property-read \App\Models\Cliente|null $cliente
 * @method static \Illuminate\Database\Eloquent\Builder|Parroquia acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Parroquia filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Parroquia ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Parroquia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Parroquia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Parroquia query()
 * @method static \Illuminate\Database\Eloquent\Builder|Parroquia setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Parroquia setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Parroquia setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Parroquia whereCantonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Parroquia whereCodParroquia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Parroquia whereCodPostal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Parroquia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Parroquia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Parroquia whereParroquia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Parroquia whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Parroquia extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    use Filterable;
    protected $table = "parroquias";
    protected $fillable = [
        'canton_id',
        'parroquia'
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];
    protected $cache = true;

    private static $whiteListFilter = ['*'];

    /*
    * Get the provincia that owns the canton
    */
    public function canton()
    {
        return $this->belongsTo(Canton::class);
    }

    public function cliente()
    {
        return $this->hasOne(Cliente::class);
    }
}
