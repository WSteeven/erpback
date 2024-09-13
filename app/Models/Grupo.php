<?php

namespace App\Models;

use App\Models\Tareas\CentroCosto;
use App\Models\Tareas\SubcentroCosto;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Grupo
 *
 * @property int $id
 * @property string $nombre
 * @property bool $activo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $coordinador_id
 * @property string|null $region
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Empleado|null $coordinador
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Empleado> $empleados
 * @property-read int|null $empleados_count
 * @property-read SubcentroCosto|null $subCentroCosto
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subtarea> $subtareas
 * @property-read int|null $subtareas_count
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo query()
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo whereCoordinadorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grupo whereUpdatedAt($value)
 * @mixin \Eloquent
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

    private static $whiteListFilter = [
        'nombre',
        'activo',
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
