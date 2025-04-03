<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Medico\AntecedenteTrabajoAnterior
 *
 * @property int $id
 * @property string $empresa
 * @property string $puesto_trabajo
 * @property string $actividades
 * @property int $tiempo_trabajo
 * @property string $observacion
 * @property int $ficha_preocupacional_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Medico\FichaPreocupacional|null $fichaPreocupacional
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\RiesgoAntecedenteEmpleoAnterior> $riesgos
 * @property-read int|null $riesgos_count
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteTrabajoAnterior newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteTrabajoAnterior newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteTrabajoAnterior query()
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteTrabajoAnterior whereActividades($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteTrabajoAnterior whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteTrabajoAnterior whereEmpresa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteTrabajoAnterior whereFichaPreocupacionalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteTrabajoAnterior whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteTrabajoAnterior whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteTrabajoAnterior wherePuestoTrabajo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteTrabajoAnterior whereTiempoTrabajo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteTrabajoAnterior whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AntecedenteTrabajoAnterior extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_antecedentes_trabajos_anteriores';
    protected $fillable = [
        'empresa',
        'puesto_trabajo',
        'actividades',
        'tiempo_trabajo', //meses
        'observacion',
        'ficha_preocupacional_id'
    ];
    public function fichaPreocupacional()
    {
        return $this->hasOne(FichaPreocupacional::class);
    }

    public function riesgos()
    {
        return $this->hasMany(RiesgoAntecedenteEmpleoAnterior::class, 'antecedente_id', 'id');
    }
}
