<?php

namespace App\Models\Vehiculos;

use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\Multas;
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
 * App\Models\Vehiculos\Conductor
 *
 * @property int $empleado_id
 * @property string $tipo_licencia
 * @property string $inicio_vigencia
 * @property string $fin_vigencia
 * @property float $puntos
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $empleado
 * @property-read Collection<int, Licencia> $licencias
 * @property-read int|null $licencias_count
 * @property-read Collection<int, MultaConductor> $multas
 * @property-read int|null $multas_count
 * @method static Builder|Conductor acceptRequest(?array $request = null)
 * @method static Builder|Conductor filter(?array $request = null)
 * @method static Builder|Conductor ignoreRequest(?array $request = null)
 * @method static Builder|Conductor newModelQuery()
 * @method static Builder|Conductor newQuery()
 * @method static Builder|Conductor query()
 * @method static Builder|Conductor setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Conductor setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Conductor setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Conductor whereCreatedAt($value)
 * @method static Builder|Conductor whereEmpleadoId($value)
 * @method static Builder|Conductor whereFinVigencia($value)
 * @method static Builder|Conductor whereInicioVigencia($value)
 * @method static Builder|Conductor wherePuntos($value)
 * @method static Builder|Conductor whereTipoLicencia($value)
 * @method static Builder|Conductor whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Conductor extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;

    protected $table = 'veh_conductores';
    protected $fillable = [
        'empleado_id',
        'tipo_licencia',
        'inicio_vigencia',
        'fin_vigencia',
        'puntos',
    ];

    protected $primaryKey = 'empleado_id';
    //obtener la llave primaria
    public function getKeyName()
    {
        return 'empleado_id';
    }
    public $incrementing = false;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static array $whiteListFilter = ['*'];


    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    /**
     * Relación uno a uno.
     * Un Conductor es un empleado.
     */
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    /**
     * Relación uno a muchos.
     * Un conductor tiene varias licencias.
     */
    public function licencias()
    {
        return $this->hasMany(Licencia::class, 'conductor_id');
    }
    /**
     * Relación uno a muchos.
     * Un Conductor tiene una o varias multas
     */
    public function multas()
    {
        return $this->hasMany(MultaConductor::class,  'empleado_id');
    }
}
