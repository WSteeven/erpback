<?php

namespace App\Models;

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
 * App\Models\InformacionMedicaEmpleado
 *
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|InformacionMedicaEmpleado newModelQuery()
 * @method static Builder|InformacionMedicaEmpleado newQuery()
 * @method static Builder|InformacionMedicaEmpleado query()
 * @mixin Eloquent
 */
class InformacionMedicaEmpleado extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_informacion_medica_empleados';
    protected $fillable = [
        'ficha_preocupacional',
        'ficha_aptitud',
        'ficha_ocupacional_periodico',
        'ficha_reingreso',
        'ficha_salida',
        'evaluacion_riesgo_psicosocial',
        'encuesta',
        'registro_examen_id',
    ];

    // Relaciones
//    public function registroExamen()
//    {
//        return $this->belongsTo(RegistroExamen::class);
//    }
}
