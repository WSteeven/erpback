<?php

namespace App\Models\Vehiculos;

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
 * App\Models\Vehiculos\Combustible
 *
 * @property int $id
 * @property string $nombre
 * @property float $precio
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Collection<int, Vehiculo> $vehiculos
 * @property-read int|null $vehiculos_count
 * @method static Builder|Combustible acceptRequest(?array $request = null)
 * @method static Builder|Combustible filter(?array $request = null)
 * @method static Builder|Combustible ignoreRequest(?array $request = null)
 * @method static Builder|Combustible newModelQuery()
 * @method static Builder|Combustible newQuery()
 * @method static Builder|Combustible query()
 * @method static Builder|Combustible setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Combustible setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Combustible setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Combustible whereCreatedAt($value)
 * @method static Builder|Combustible whereId($value)
 * @method static Builder|Combustible whereNombre($value)
 * @method static Builder|Combustible wherePrecio($value)
 * @method static Builder|Combustible whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Combustible extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'combustibles';
    protected $fillable =['nombre', 'precio'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static array $whiteListFilter = [
        '*',
    ];

    /**
     * Relación uno a muchos
     */
    public function vehiculos(){
        return $this->hasMany(Vehiculo::class);
    }
}
