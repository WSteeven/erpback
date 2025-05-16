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
 * App\Models\Condicion
 *
 * @property int $id
 * @property string $nombre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, ActivoFijo> $activos
 * @property-read int|null $activos_count
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Inventario|null $inventario
 * @method static Builder|Condicion acceptRequest(?array $request = null)
 * @method static Builder|Condicion filter(?array $request = null)
 * @method static Builder|Condicion ignoreRequest(?array $request = null)
 * @method static Builder|Condicion newModelQuery()
 * @method static Builder|Condicion newQuery()
 * @method static Builder|Condicion query()
 * @method static Builder|Condicion setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Condicion setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Condicion setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Condicion whereCreatedAt($value)
 * @method static Builder|Condicion whereId($value)
 * @method static Builder|Condicion whereNombre($value)
 * @method static Builder|Condicion whereUpdatedAt($value)
 * @mixin Eloquent
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

    private static array $whiteListFilter = [
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
