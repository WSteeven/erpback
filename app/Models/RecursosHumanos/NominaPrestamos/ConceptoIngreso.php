<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\RecursosHumanos\NominaPrestamos\ConceptoIngreso
 *
 * @property int $id
 * @property string $nombre
 * @property int $calculable_iess
 * @property string $abreviatura
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Collection<int, IngresoRolPago> $ingreso_rol_pago_info
 * @property-read int|null $ingreso_rol_pago_info_count
 * @method static Builder|ConceptoIngreso acceptRequest(?array $request = null)
 * @method static Builder|ConceptoIngreso filter(?array $request = null)
 * @method static Builder|ConceptoIngreso ignoreRequest(?array $request = null)
 * @method static Builder|ConceptoIngreso newModelQuery()
 * @method static Builder|ConceptoIngreso newQuery()
 * @method static Builder|ConceptoIngreso query()
 * @method static Builder|ConceptoIngreso setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|ConceptoIngreso setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|ConceptoIngreso setLoadInjectedDetection($load_default_detection)
 * @method static Builder|ConceptoIngreso whereAbreviatura($value)
 * @method static Builder|ConceptoIngreso whereCalculableIess($value)
 * @method static Builder|ConceptoIngreso whereCreatedAt($value)
 * @method static Builder|ConceptoIngreso whereId($value)
 * @method static Builder|ConceptoIngreso whereNombre($value)
 * @method static Builder|ConceptoIngreso whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ConceptoIngreso extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;

    protected $table = 'concepto_ingresos';
    protected $fillable = [
        'nombre',
        'calculable_iess',
        'abreviatura',
    ];

    protected $casts = [
        'calculable_iess' => 'boolean'
    ];

    const BONIFICACION_ID = 4;
    const   VACACION = 'VACACION';
    private static array $whiteListFilter = [
        'id',
        'nombre',
        'calculable_iess'
    ];

    /**
     * Crea o busca un concepto de ingresos de vacacion y retorna el id del mismo
     * @return HigherOrderBuilderProxy|int|mixed
     */
    public static function getOrCreateConceptoVacacion()
    {
        // se obtiene el id o se crea vacaciones en caso de que no haya
        $conceptoVacacion = ConceptoIngreso::where('nombre', ConceptoIngreso::VACACION)->first();
        if (!$conceptoVacacion) {
            $conceptoVacacion = ConceptoIngreso::create(['nombre' => ConceptoIngreso::VACACION,
                'calculable_iess' => false,
                'abreviatura' => 'VAC']);
        }
        return $conceptoVacacion->id;
    }

    public function ingreso_rol_pago_info()
    {
        return $this->hasMany(IngresoRolPago::class, 'id', 'concepto');
    }
}
