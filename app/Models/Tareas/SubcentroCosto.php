<?php

namespace App\Models\Tareas;

use App\Models\Grupo;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Tareas\SubcentroCosto
 *
 * @property int $id
 * @property string $nombre
 * @property int|null $centro_costo_id
 * @property int|null $grupo_id
 * @property bool $activo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Tareas\CentroCosto|null $centro
 * @property-read Grupo|null $grupo
 * @method static \Illuminate\Database\Eloquent\Builder|SubcentroCosto acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SubcentroCosto filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SubcentroCosto ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SubcentroCosto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubcentroCosto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubcentroCosto query()
 * @method static \Illuminate\Database\Eloquent\Builder|SubcentroCosto setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SubcentroCosto setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SubcentroCosto setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|SubcentroCosto whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubcentroCosto whereCentroCostoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubcentroCosto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubcentroCosto whereGrupoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubcentroCosto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubcentroCosto whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubcentroCosto whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SubcentroCosto extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait, Filterable;
    use AuditableModel;
    protected $table = 'tar_subcentros_costos';
    protected $fillable = [
        'nombre', 'centro_costo_id', 'activo', 'grupo_id'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo' => 'boolean',
    ];

    private static $whiteListFilter = [
        '*',
    ];

    /**
     * Relacion uno a muchos inversa.
     * Uno o varios subcentros de costos pertenecen a un centro de costos general.
     */
    public function centro()
    {
        return $this->belongsTo(CentroCosto::class, 'centro_costo_id', 'id');
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }
}
