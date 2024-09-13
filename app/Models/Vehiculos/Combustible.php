<?php

namespace App\Models\Vehiculos;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Vehiculos\Combustible
 *
 * @property int $id
 * @property string $nombre
 * @property float $precio
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Vehiculos\Vehiculo> $vehiculos
 * @property-read int|null $vehiculos_count
 * @method static \Illuminate\Database\Eloquent\Builder|Combustible acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Combustible filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Combustible ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Combustible newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Combustible newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Combustible query()
 * @method static \Illuminate\Database\Eloquent\Builder|Combustible setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Combustible setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Combustible setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Combustible whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Combustible whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Combustible whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Combustible wherePrecio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Combustible whereUpdatedAt($value)
 * @mixin \Eloquent
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

    private static $whiteListFilter = [
        '*',
    ];

    /**
     * Relación uno a muchos
     */
    public function vehiculos(){
        return $this->hasMany(Vehiculo::class);
    }
}
