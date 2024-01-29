<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class AntecedenteTrabajoAnterior extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_antecedentes_trabajos_anteriores';
    protected $fillable = [
        'empresa',
        'puesto_trabajo',
        'actividades_desempenaba',
        'tiempo_tabajo',
        'r_fisico',
        'r_mecanico',
        'r_quimico',
        'r_biologico',
        'r_ergonomico',
        'r_phisosocial',
        'observacion',
        'preocupacional_id'
    ];
    public function preocupacional()
    {
        return $this->hasOne(Preocupacional::class, 'id', 'preocupacional_id');
    }
}
