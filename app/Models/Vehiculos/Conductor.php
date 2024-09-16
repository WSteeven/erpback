<?php

namespace App\Models\Vehiculos;

use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\Multas;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Vehiculos\Conductor
 *
 * @property int $empleado_id
 * @property string $tipo_licencia
 * @property string $inicio_vigencia
 * @property string $fin_vigencia
 * @property float $puntos
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $empleado
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Vehiculos\Licencia> $licencias
 * @property-read int|null $licencias_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Vehiculos\MultaConductor> $multas
 * @property-read int|null $multas_count
 * @method static \Illuminate\Database\Eloquent\Builder|Conductor acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Conductor filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Conductor ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Conductor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Conductor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Conductor query()
 * @method static \Illuminate\Database\Eloquent\Builder|Conductor setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Conductor setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Conductor setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Conductor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conductor whereEmpleadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conductor whereFinVigencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conductor whereInicioVigencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conductor wherePuntos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conductor whereTipoLicencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conductor whereUpdatedAt($value)
 * @mixin \Eloquent
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

    private static $whiteListFilter = ['*'];


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
