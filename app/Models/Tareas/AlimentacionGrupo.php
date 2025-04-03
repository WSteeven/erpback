<?php

namespace App\Models\Tareas;

use Src\App\WhereRelationLikeCondition\Tareas\GrupoWRLC;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Models\FondosRotativos\Gasto\SubDetalleViatico;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UppercaseValuesTrait;
use Laravel\Scout\Searchable;
use App\Models\Grupo;
use App\Models\Subtarea;
use App\Models\Tarea;
use Src\App\WhereRelationLikeCondition\Tareas\CoordinadorWRLC;
use Src\App\WhereRelationLikeCondition\Tareas\TareaWRLC;
use Src\App\WhereRelationLikeCondition\Tareas\TipoAlimentacionWRLC;

/**
 * App\Models\Tareas\AlimentacionGrupo
 *
 * @property int $id
 * @property string|null $observacion
 * @property int $cantidad_personas
 * @property float $precio
 * @property string $fecha
 * @property int $tarea_id
 * @property int|null $subtarea_id
 * @property int|null $grupo_id
 * @property int|null $tipo_alimentacion_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Grupo|null $grupo
 * @property-read Subtarea|null $subtarea
 * @property-read Tarea|null $tarea
 * @property-read SubDetalleViatico|null $tipoAlimentacion
 * @method static \Illuminate\Database\Eloquent\Builder|AlimentacionGrupo acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AlimentacionGrupo filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AlimentacionGrupo ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AlimentacionGrupo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AlimentacionGrupo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AlimentacionGrupo query()
 * @method static \Illuminate\Database\Eloquent\Builder|AlimentacionGrupo setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AlimentacionGrupo setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AlimentacionGrupo setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|AlimentacionGrupo whereCantidadPersonas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlimentacionGrupo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlimentacionGrupo whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlimentacionGrupo whereGrupoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlimentacionGrupo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlimentacionGrupo whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlimentacionGrupo wherePrecio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlimentacionGrupo whereSubtareaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlimentacionGrupo whereTareaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlimentacionGrupo whereTipoAlimentacionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlimentacionGrupo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AlimentacionGrupo extends Model implements Auditable
{
    use HasFactory, Filterable, AuditableModel, UppercaseValuesTrait, Searchable;

    protected $table = 'tar_alimentacion_grupos';
    protected $fillable = [
        'observacion',
        'cantidad_personas',
        'precio',
        'fecha',
        'subtarea_id',
        'tarea_id',
        'grupo_id',
        'tipo_alimentacion_id',
    ];

    /*******************
     * Eloquent Filter
     *******************/
    private static $whiteListFilter = [
        '*',
    ];

    private $aliasListFilter = [
        'grupo.nombre' => 'grupo',
        'tarea.codigo_tarea' => 'tarea',
        'tipo_alimentacion.descripcion' => 'tipo_alimentacion',
        'coordinador.nombres_apellidos' => 'coordinador',
    ];

    public function EloquentFilterCustomDetection(): array
    {
        return [
            GrupoWRLC::class,
            TareaWRLC::class,
            TipoAlimentacionWRLC::class,
            CoordinadorWRLC::class,
        ];
    }

    /*************************
     * Laravel Scout Search
     *************************/
    public function toSearchableArray()
    {
        $coordinador = $this->tarea?->coordinador;

        return [
            'grupo' => $this->grupo->nombre,
            'tarea' => $this->tarea->codigo_tarea,
            'coordinador' => $coordinador ? $coordinador->nombres . ' ' . $coordinador->apellidos : null,
            'tipo_alimentacion' => $this->tipoAlimentacion->descripcion,
        ];
    }

    /**************
     * Constantes
     **************/
    const PRECIO_ALIMENTACION = 3;

    /**************
     * Relaciones
     **************/
    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }

    public function subtarea()
    {
        return $this->belongsTo(Subtarea::class);
    }

    public function grupo()
    {
        // return $this->belongsTo(Grupo::class);
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }

    public function tipoAlimentacion()
    {
        return $this->belongsTo(SubDetalleViatico::class);
    }
}
