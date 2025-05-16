<?php

namespace App\Models\Medico;


use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * App\Models\Medico\ActividadFisica
 *
 * @property int $id
 * @property string $nombre_actividad
 * @property string $tiempo
 * @property int $actividable_id
 * @property string $actividable_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $actividable
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadFisica newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadFisica newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadFisica query()
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadFisica whereActividableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadFisica whereActividableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadFisica whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadFisica whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadFisica whereNombreActividad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadFisica whereTiempo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActividadFisica whereUpdatedAt($value)
 * @mixin \Eloquent
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
