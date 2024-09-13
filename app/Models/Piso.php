<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Piso
 *
 * @property int $id
 * @property string $fila
 * @property string|null $columna
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ubicacion> $ubicaciones
 * @property-read int|null $ubicaciones_count
 * @method static \Illuminate\Database\Eloquent\Builder|Piso acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Piso filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Piso ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Piso newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Piso newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Piso query()
 * @method static \Illuminate\Database\Eloquent\Builder|Piso setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Piso setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Piso setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Piso whereColumna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Piso whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Piso whereFila($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Piso whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Piso whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Piso extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    use Filterable;
    
    protected $table = 'pisos';
    protected $fillable = ['fila','columna'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];
    private static $whiteListFilter = [
        '*',
    ];


    /* public function perchas()
    {
        return $this->belongsToMany(Percha::class);
    } */

    /**
     * Relacion uno a muchos
     * Varias ubicaciones en un piso
     */
    public function ubicaciones(){
        return $this->hasMany(Ubicacion::class);
    }
}
