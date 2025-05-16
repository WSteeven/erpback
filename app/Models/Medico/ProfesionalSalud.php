<?php

namespace App\Models\Medico;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Medico\ProfesionalSalud
 *
 * @property int $id
 * @property string $codigo
 * @property int $empleado_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $empleado
 * @method static \Illuminate\Database\Eloquent\Builder|ProfesionalSalud acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfesionalSalud filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfesionalSalud ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfesionalSalud newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfesionalSalud newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfesionalSalud query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfesionalSalud setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfesionalSalud setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfesionalSalud setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfesionalSalud whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfesionalSalud whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfesionalSalud whereEmpleadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfesionalSalud whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfesionalSalud whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProfesionalSalud extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_profesionales_salud';
    protected $fillable = [
        'codigo',
        'empleado_id'
    ];

    protected $primaryKey = 'empleado_id'; // Especifica que user_id es la clave primaria
    public function getKeyName()
    {
        return 'empleado_id';
    }
    public $incrementing = false;

    private static $whiteListFilter = ['*'];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
