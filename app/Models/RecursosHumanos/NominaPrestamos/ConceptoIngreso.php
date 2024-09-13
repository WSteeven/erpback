<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\RecursosHumanos\NominaPrestamos\ConceptoIngreso
 *
 * @property int $id
 * @property string $nombre
 * @property int $calculable_iess
 * @property string $abreviatura
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RecursosHumanos\NominaPrestamos\IngresoRolPago> $ingreso_rol_pago_info
 * @property-read int|null $ingreso_rol_pago_info_count
 * @method static \Illuminate\Database\Eloquent\Builder|ConceptoIngreso acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ConceptoIngreso filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ConceptoIngreso ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ConceptoIngreso newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConceptoIngreso newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConceptoIngreso query()
 * @method static \Illuminate\Database\Eloquent\Builder|ConceptoIngreso setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ConceptoIngreso setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ConceptoIngreso setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|ConceptoIngreso whereAbreviatura($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConceptoIngreso whereCalculableIess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConceptoIngreso whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConceptoIngreso whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConceptoIngreso whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConceptoIngreso whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ConceptoIngreso extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'concepto_ingresos';
    protected $fillable = [
        'nombre',
        'calculable_iess'
    ];

    private static $whiteListFilter = [
        'id',
        'nombre',
        'calculable_iess'
    ];
    public function ingreso_rol_pago_info()
    {
        return $this->hasMany(IngresoRolPago::class, 'id', 'concepto');
    }
}
