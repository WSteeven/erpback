<?php

namespace App\Models\Medico;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Medico\AntecedenteGinecoObstetrico
 *
 * @property int $id
 * @property string $menarquia
 * @property string $ciclos
 * @property string $fecha_ultima_menstruacion
 * @property int $gestas
 * @property int $partos
 * @property int $cesareas
 * @property int $abortos
 * @property int $antecedente_personal_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read AntecedentePersonal|null $antecedentesPersonales
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|AntecedenteGinecoObstetrico newModelQuery()
 * @method static Builder|AntecedenteGinecoObstetrico newQuery()
 * @method static Builder|AntecedenteGinecoObstetrico query()
 * @method static Builder|AntecedenteGinecoObstetrico whereAbortos($value)
 * @method static Builder|AntecedenteGinecoObstetrico whereAntecedentePersonalId($value)
 * @method static Builder|AntecedenteGinecoObstetrico whereCesareas($value)
 * @method static Builder|AntecedenteGinecoObstetrico whereCiclos($value)
 * @method static Builder|AntecedenteGinecoObstetrico whereCreatedAt($value)
 * @method static Builder|AntecedenteGinecoObstetrico whereFechaUltimaMenstruacion($value)
 * @method static Builder|AntecedenteGinecoObstetrico whereGestas($value)
 * @method static Builder|AntecedenteGinecoObstetrico whereId($value)
 * @method static Builder|AntecedenteGinecoObstetrico whereMenarquia($value)
 * @method static Builder|AntecedenteGinecoObstetrico wherePartos($value)
 * @method static Builder|AntecedenteGinecoObstetrico whereUpdatedAt($value)
 * @mixin Eloquent
 */
class AntecedenteGinecoObstetrico extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_antecedentes_gineco_obstetricos';
    protected $fillable = [
        'menarquia',
        'ciclos',
        'fecha_ultima_menstruacion',
        'gestas',
        'partos',
        'cesareas',
        'abortos',
        'antecedente_personal_id',
    ];
    public function antecedentesPersonales()
    {
        return $this->hasOne(AntecedentePersonal::class, 'id', 'antecedente_personal_id');
    }
}
