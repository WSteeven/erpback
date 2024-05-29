<?php

namespace App\Models\Medico;


use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class OpcionRespuestaTipoEvaluacionMedicaRetiro extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_opciones_respuestas_tipos_evaluaciones_medicas_retiros';
    protected $fillable = [
        'respuesta',
        'tipo_evaluacion_medica_retiro_id',
        'ficha_aptitud_id',
    ];

    private static $whiteListFilter = ['*'];
}
