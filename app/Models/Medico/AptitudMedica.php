<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class AptitudMedica extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_aptitudes_medicas';
    protected $fillable = [
        'tipo_aptitud_id',
        'observacion',
        'limitacion',
        'aptitudable_id',
        'aptitudable_type',
    ];

    public function tipoAptitud()
    {
        return $this->hasOne(TipoAptitud::class, 'id', 'tipo_aptitud_id');
    }
    public function fichaPreocupacional()
    {
        return $this->hasOne(FichaPreocupacional::class, 'id', 'ficha_preocupacional_id');
    }
}
