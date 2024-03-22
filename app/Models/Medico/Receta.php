<?php

namespace App\Models\Medico;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;

class Receta extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'med_recetas';
    protected $fillable = [
        'rp',
        'prescripcion',
        'consulta_medica_id',
        // 'cita_medica_id',
        // 'registro_empleado_examen_id',
    ];

    private static $whiteListFilter = ['*'];

    public function consultaMedica()
    {
        return $this->belongsTo(ConsultaMedica::class);
    }
}
