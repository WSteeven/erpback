<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;


class DescripcionAntecedenteTrabajo extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_descripciones_antecedentes_trabajos';
    protected $fillable = [
        'calificado_iess',
        'descripcion',
        'fecha',
        'observacion',
        'tipo_descripcion_antecedente_trabajo',
        'ficha_preocupacional_id'
    ];
    public function fichaPreocupacional()
    {
        return $this->hasOne(FichaPreocupacional::class, 'id', 'ficha_preocupacional_id');
    }
}


/**
 * Esta tabla no se usa ni ningun elemento relacionado
 */