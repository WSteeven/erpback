<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class PlanVacacion extends Model implements  Auditable
{
    use HasFactory, Filterable, UppercaseValuesTrait;
    use AuditableModel;

    protected $table = 'rrhh_nomina_planes_vacaciones';
    protected $fillable = [
        'periodo_id',
        'empleado_id',
        'rangos',
        'fecha_inicio',
        'fecha_fin',
        'fecha_inicio_primer_rango',
        'fecha_fin_primer_rango',
        'fecha_inicio_segundo_rango',
        'fecha_fin_segundo_rango',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static array $whiteListFilter = ['*'];


    public function periodo(){
        return $this->belongsTo(Periodo::class);
    }

    public function empleado(){
        return $this->belongsTo(Empleado::class);
    }


}
