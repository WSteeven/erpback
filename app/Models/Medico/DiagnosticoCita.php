<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class DiagnosticoCita extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_diagnosticos_citas';
    protected $fillable = [
        'recomendacion',
        'cie_id',
    ];

    public function cie()
    {
        return $this->belongsTo(Cie::class); //, 'cie_id');
    }
}
