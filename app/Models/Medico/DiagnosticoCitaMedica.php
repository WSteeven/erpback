<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class DiagnosticoCitaMedica extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_diagnosticos_cita_medica';
    protected $fillable = [
        'recomendacion',
        'cie_id',
        'consulta_medica_id',
        // 'cita_medica_id',
        // 'registro_empleado_examen_id',
    ];

    private static $whiteListFilter = ['*'];

    public function cie()
    {
        return $this->belongsTo(Cie::class); //, 'cie_id');
    }

    public function citaMedica()
    {
        return $this->belongsTo(CitaMedica::class);
    }
}
