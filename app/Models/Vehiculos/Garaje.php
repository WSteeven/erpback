<?php

namespace App\Models\Vehiculos;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Vehiculos\Garaje
 *
 * @property int $id
 * @property string $nombre
 * @property bool $activo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|Garaje acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Garaje filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Garaje ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Garaje newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Garaje newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Garaje query()
 * @method static \Illuminate\Database\Eloquent\Builder|Garaje setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Garaje setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Garaje setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Garaje whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Garaje whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Garaje whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Garaje whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Garaje whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Garaje extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;
    
    protected $table = 'veh_garajes';
    protected $fillable = [
        'nombre',
        'activo',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo' => 'boolean'
    ];


    private static $whiteListFilter = ['*'];

    
}
