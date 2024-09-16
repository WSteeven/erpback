<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Medico\AptitudMedica
 *
 * @property int $id
 * @property int $tipo_aptitud_id
 * @property string|null $observacion
 * @property string|null $limitacion
 * @property int $aptitudable_id
 * @property string $aptitudable_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $aptitudable
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Medico\TipoAptitud|null $tipoAptitud
 * @method static \Illuminate\Database\Eloquent\Builder|AptitudMedica newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AptitudMedica newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AptitudMedica query()
 * @method static \Illuminate\Database\Eloquent\Builder|AptitudMedica whereAptitudableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AptitudMedica whereAptitudableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AptitudMedica whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AptitudMedica whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AptitudMedica whereLimitacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AptitudMedica whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AptitudMedica whereTipoAptitudId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AptitudMedica whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AptitudMedica extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_aptitudes_medicas';
    protected $fillable = [
        'tipo_aptitud_id',
        'observacion',
        'limitacion',
        'aptitudable_id',
        'aptitudable_type',
    ];

    public function tipoAptitud()
    {
        return $this->hasOne(TipoAptitud::class, 'id', 'tipo_aptitud_id');
    }
    public function aptitudable()
    {
        return $this->morphTo();
    }
}
