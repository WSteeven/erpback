<?php

namespace App\Models\Tareas;

use App\Models\Empleado;
use App\Models\Proyecto;
use App\Models\Tarea;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Tareas\Etapa
 *
 * @property int $id
 * @property string $nombre
 * @property int|null $responsable_id
 * @property int|null $proyecto_id
 * @property bool $activo
 * @property string|null $motivo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Proyecto|null $proyecto
 * @property-read Empleado|null $responsable
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Tarea> $tareas
 * @property-read int|null $tareas_count
 * @method static \Illuminate\Database\Eloquent\Builder|Etapa acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Etapa filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Etapa ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Etapa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Etapa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Etapa query()
 * @method static \Illuminate\Database\Eloquent\Builder|Etapa setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Etapa setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Etapa setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Etapa whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Etapa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Etapa whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Etapa whereMotivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Etapa whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Etapa whereProyectoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Etapa whereResponsableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Etapa whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Etapa extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;

    protected $table = 'tar_etapas';
    protected $fillable = [
        'nombre',
        'activo',
        'responsable_id',
        'proyecto_id',
        'motivo',
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
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

    /**
     * Relación uno a muchos (inversa).
     */
    public function responsable()
    {
        return $this->belongsTo(Empleado::class);
    }
    /**
     * Relación uno a muchos (inversa).
     */
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function tareas()
    {
        return $this->hasMany(Tarea::class);
    }
}
