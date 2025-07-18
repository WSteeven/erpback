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
 * App\Models\Medico\AntecedenteTrabajoAnterior
 *
 * @property int $id
 * @property string $empresa
 * @property string $puesto_trabajo
 * @property string $actividades
 * @property int $tiempo_trabajo
 * @property string $observacion
 * @property int $ficha_preocupacional_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read FichaPreocupacional|null $fichaPreocupacional
 * @property-read Collection<int, RiesgoAntecedenteEmpleoAnterior> $riesgos
 * @property-read int|null $riesgos_count
 * @method static Builder|AntecedenteTrabajoAnterior newModelQuery()
 * @method static Builder|AntecedenteTrabajoAnterior newQuery()
 * @method static Builder|AntecedenteTrabajoAnterior query()
 * @method static Builder|AntecedenteTrabajoAnterior whereActividades($value)
 * @method static Builder|AntecedenteTrabajoAnterior whereCreatedAt($value)
 * @method static Builder|AntecedenteTrabajoAnterior whereEmpresa($value)
 * @method static Builder|AntecedenteTrabajoAnterior whereFichaPreocupacionalId($value)
 * @method static Builder|AntecedenteTrabajoAnterior whereId($value)
 * @method static Builder|AntecedenteTrabajoAnterior whereObservacion($value)
 * @method static Builder|AntecedenteTrabajoAnterior wherePuestoTrabajo($value)
 * @method static Builder|AntecedenteTrabajoAnterior whereTiempoTrabajo($value)
 * @method static Builder|AntecedenteTrabajoAnterior whereUpdatedAt($value)
 * @mixin Eloquent
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
