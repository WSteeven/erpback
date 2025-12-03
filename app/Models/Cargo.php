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
 * App\Models\Cargo
 *
 * @property int $id
 * @property string $nombre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property bool $estado
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Collection<int, Empleado> $empleados
 * @property-read int|null $empleados_count
 * @method static Builder|Cargo acceptRequest(?array $request = null)
 * @method static Builder|Cargo filter(?array $request = null)
 * @method static Builder|Cargo ignoreRequest(?array $request = null)
 * @method static Builder|Cargo newModelQuery()
 * @method static Builder|Cargo newQuery()
 * @method static Builder|Cargo query()
 * @method static Builder|Cargo setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Cargo setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Cargo setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Cargo whereCreatedAt($value)
 * @method static Builder|Cargo whereEstado($value)
 * @method static Builder|Cargo whereId($value)
 * @method static Builder|Cargo whereNombre($value)
 * @method static Builder|Cargo whereUpdatedAt($value)
 * @property bool $aprobado_rrhh
 * @method static Builder|Cargo whereAprobadoRrhh($value)
 * @mixin Eloquent
 */
class Cargo extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;

    protected $table = 'cargos';
    protected $fillable = [
        'nombre',
        'area', // nullable, esto sirve para el rol de pagos
        'estado',
        'aprobado_rrhh'
    ];

    private static array $whiteListFilter = [
        'id',
        'nombre',
        'estado',
        'aprobado_rrhh',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'estado' => 'boolean',
        'aprobado_rrhh' => 'boolean',
    ];

    public function toSearchableArray()
    {
        return [
            'nombres' => $this->nombres,
        ];
    }


    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

    /**
     * Relación uno a uno (inversa).
     * Un cargo pertenece a un empleado.
     */
    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }
}
