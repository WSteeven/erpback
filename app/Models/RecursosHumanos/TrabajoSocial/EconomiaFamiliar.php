<?php

namespace App\Models\RecursosHumanos\TrabajoSocial;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;


class EconomiaFamiliar extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait, Filterable;
    use AuditableModel;

    protected $table = 'rrhh_ts_economias_familiares';
    protected $fillable = [
        'visita_id',
        'empleado_id',
        'ingresos',
        'eg_vivienda',
        'eg_servicios_basicos',
        'eg_educacion',
        'eg_salud',
        'eg_vestimenta',
        'eg_alimentacion',
        'eg_transporte',
        'eg_prestamos',
        'eg_otros_gastos',
    ];

    protected $casts = [
        'ingresos' => 'array',
    ];

    public function visita()
    {
        return $this->belongsTo(VisitaDomiciliaria::class, 'visita_id', 'id');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

}
