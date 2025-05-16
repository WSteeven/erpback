<?php

namespace App\Models;

use App\Models\Tareas\SubcentroCosto;
use App\Traits\UppercaseValuesTrait;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Grupo
 *
 * @property int $id
 * @property string $nombre
 * @property bool $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $coordinador_id
 * @property string|null $region
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $coordinador
 * @property-read Collection<int, Empleado> $empleados
 * @property-read int|null $empleados_count
 * @property-read SubcentroCosto|null $subCentroCosto
 * @property-read Collection<int, Subtarea> $subtareas
 * @property-read int|null $subtareas_count
 * @method static Builder|Grupo acceptRequest(?array $request = null)
 * @method static Builder|Grupo filter(?array $request = null)
 * @method static Builder|Grupo ignoreRequest(?array $request = null)
 * @method static Builder|Grupo newModelQuery()
 * @method static Builder|Grupo newQuery()
 * @method static Builder|Grupo query()
 * @method static Builder|Grupo setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Grupo setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Grupo setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Grupo whereActivo($value)
 * @method static Builder|Grupo whereCoordinadorId($value)
 * @method static Builder|Grupo whereCreatedAt($value)
 * @method static Builder|Grupo whereId($value)
 * @method static Builder|Grupo whereNombre($value)
 * @method static Builder|Grupo whereRegion($value)
 * @method static Builder|Grupo whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Grupo extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    const R1 = 'R1';
    const R2 = 'R2';
    const R3 = 'R3';
    const R4 = 'R4';
    const R5 = 'R5';

    protected $table = 'grupos';
    protected $fillable = ['nombre', 'region', 'activo', 'coordinador_id'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo' => 'boolean',
    ];

    private static array $whiteListFilter = [
        'nombre',
        'activo',
        'region',
        'coordinador_id',
    ];

    /*public function tareas()
    {
        return $this->belongsToMany(Tarea::class);
    }*/

    // eliminar
    /*public function subtareas()
    {
        return $this->belongsToMany(Subtarea::class);
    } */

    public function empleados(){
        return $this->hasMany(Empleado::class);
    }

    public function subtareas()
    {
        return $this->hasMany(Subtarea::class);
    }

    // public function controlMaterialesSubtareas()
    // {
    //     return $this->hasMany(ControlMaterialTrabajo::class);
    // }

    public function coordinador()
    {
        return $this->belongsTo(Empleado::class, 'coordinador_id', 'id');
    }
    public function subCentroCosto()
    {
        return $this->belongsTo(SubcentroCosto::class, 'id', 'grupo_id');
    }

}
