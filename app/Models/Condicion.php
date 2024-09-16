<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Condicion
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ActivoFijo> $activos
 * @property-read int|null $activos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Inventario|null $inventario
 * @method static \Illuminate\Database\Eloquent\Builder|Condicion acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Condicion filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Condicion ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Condicion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Condicion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Condicion query()
 * @method static \Illuminate\Database\Eloquent\Builder|Condicion setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Condicion setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Condicion setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Condicion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Condicion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Condicion whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Condicion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Condicion extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable;
    use AuditableModel;
    protected $table = 'condiciones_de_productos';
    protected $fillable = ['nombre'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    const NUEVO = 1;
    const USADO = 2;

    private static $whiteListFilter = [
        '*',
    ];

    /**
     * Relacion uno a uno
     */
    public function inventario()
    {
        return $this->hasOne(Inventario::class);
    }
    
    /**
     * Relación uno a muchos.
     * Una condicion puede estar en uno o muchos activos fijos.
     */
    public function activos()
    {
        return $this->hasMany(ActivoFijo::class);
    }
}
