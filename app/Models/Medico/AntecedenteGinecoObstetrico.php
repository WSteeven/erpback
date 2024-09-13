<?php

namespace App\Models\Medico;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Medico\AntecedentePersonal|null $antecedentesPersonales
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteGinecoObstetrico newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteGinecoObstetrico newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteGinecoObstetrico query()
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteGinecoObstetrico whereAbortos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteGinecoObstetrico whereAntecedentePersonalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteGinecoObstetrico whereCesareas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteGinecoObstetrico whereCiclos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteGinecoObstetrico whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteGinecoObstetrico whereFechaUltimaMenstruacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteGinecoObstetrico whereGestas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteGinecoObstetrico whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteGinecoObstetrico whereMenarquia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteGinecoObstetrico wherePartos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteGinecoObstetrico whereUpdatedAt($value)
 * @mixin \Eloquent
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
