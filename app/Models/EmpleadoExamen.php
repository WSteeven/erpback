<?php

namespace App\Models;

use App\Models\Medico\EstadoExamen;
use App\Models\Medico\Examen;
use App\Models\Medico\ResultadoExamen;
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
 * App\Models\EmpleadoExamen
 *
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|EmpleadoExamen newModelQuery()
 * @method static Builder|EmpleadoExamen newQuery()
 * @method static Builder|EmpleadoExamen query()
 * @property-read EstadoExamen|null $estadoExamen
 * @property-read Examen|null $examen
 * @property-read ResultadoExamen|null $resultadoExamen
 * @mixin Eloquent
 */
class EmpleadoExamen extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_empleado_examen';
    protected $fillable = [
        'estado_examen_id',
        'registro_examen_id',
        'examen_id',
    ];

    // Relaciones
    public function estadoExamen()
    {
        return $this->belongsTo(EstadoExamen::class);
    }

//    public function registroExamen()
//    {
//        return $this->belongsTo(RegistroEmpleadoExamen::class);
//    }

    public function examen()
    {
        return $this->belongsTo(Examen::class);
    }

    public function resultadoExamen()
    {
        return $this->hasOne(ResultadoExamen::class);
    }
}
