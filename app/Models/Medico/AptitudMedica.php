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
 * App\Models\Medico\AptitudMedica
 *
 * @property int $id
 * @property int $tipo_aptitud_id
 * @property string|null $observacion
 * @property string|null $limitacion
 * @property int $aptitudable_id
 * @property string $aptitudable_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent $aptitudable
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read TipoAptitud|null $tipoAptitud
 * @method static Builder|AptitudMedica newModelQuery()
 * @method static Builder|AptitudMedica newQuery()
 * @method static Builder|AptitudMedica query()
 * @method static Builder|AptitudMedica whereAptitudableId($value)
 * @method static Builder|AptitudMedica whereAptitudableType($value)
 * @method static Builder|AptitudMedica whereCreatedAt($value)
 * @method static Builder|AptitudMedica whereId($value)
 * @method static Builder|AptitudMedica whereLimitacion($value)
 * @method static Builder|AptitudMedica whereObservacion($value)
 * @method static Builder|AptitudMedica whereTipoAptitudId($value)
 * @method static Builder|AptitudMedica whereUpdatedAt($value)
 * @mixin Eloquent
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
