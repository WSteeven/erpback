<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class DiagnosticoFicha extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;
    protected $table = 'med_diagnosticos_fichas';
    protected $fillable = [
        'diagnostico_id',
        'tipo', //presuntivo o definitivo
        'diagnosticable_id',
        'diagnosticable_type',
    ];

    const PRESUNTIVO = 'PRESUNTIVO';
    const DEFINITIVO = 'DEFINITIVO';

    public function diagnostico()
    {
        return $this->belongsTo(DiagnosticoCitaMedica::class);
    }
    public function diagnosticable()
    {
        return $this->morphTo();
    }
}
