<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Medico\MedicacionConsulta
 *
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Receta|null $receta
 * @method static Builder|MedicacionConsulta newModelQuery()
 * @method static Builder|MedicacionConsulta newQuery()
 * @method static Builder|MedicacionConsulta query()
 * @property-read ConsultaMedica|null $consulta
 * @mixin Eloquent
 */
class MedicacionConsulta extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_medicaciones_consultas';
    protected $fillable = [
        'consulta_id',
        'receta_id',
    ];
    public function consulta()
    {
        return $this->belongsTo(ConsultaMedica::class, 'consulta_id');
    }
    public function receta()
    {
        return $this->belongsTo(Receta::class, 'receta_id');
    }
}
