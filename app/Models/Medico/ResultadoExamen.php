<?php

namespace App\Models\Medico;

use App\Models\Empleado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class ResultadoExamen extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_resultados_examenes';
    protected $fillable = [
        'resultado',
        'observaciones',
        'configuracion_examen_campo_id',
        'examen_solicitado_id',
    ];
    private static $whiteListFilter = ['*'];

    public function configuracionExamenCampo()
    {
        return $this->hasOne(ConfiguracionExamenCampo::class);
    }

    public function estadoSolicitudExamen()
    {
        return $this->hasOne(EstadoSolicitudExamen::class, 'examen_solicitado_id', 'id');
    }
}
