<?php

namespace App\Models\Medico;


use App\Traits\UppercaseValuesTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;


/**
 * App\Models\Medico\ActividadFisica
 *
 * @property int $id
 * @property string $nombre_actividad
 * @property string $tiempo
 * @property int $actividable_id
 * @property string $actividable_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent $actividable
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|ActividadFisica newModelQuery()
 * @method static Builder|ActividadFisica newQuery()
 * @method static Builder|ActividadFisica query()
 * @method static Builder|ActividadFisica whereActividableId($value)
 * @method static Builder|ActividadFisica whereActividableType($value)
 * @method static Builder|ActividadFisica whereCreatedAt($value)
 * @method static Builder|ActividadFisica whereId($value)
 * @method static Builder|ActividadFisica whereNombreActividad($value)
 * @method static Builder|ActividadFisica whereTiempo($value)
 * @method static Builder|ActividadFisica whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ActividadFisica extends Model  implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_actividades_fisicas';
    protected $fillable = [
        'nombre_actividad',
        'tiempo',
        'actividable_id',
        'actividable_type'
    ];

    // Relación polimorfica
    public function actividable()
    {
        return $this->morphTo();
    }
}
