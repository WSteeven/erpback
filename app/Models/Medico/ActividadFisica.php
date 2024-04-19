<?php

namespace App\Models\Medico;


use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;


class ActividadFisica extends Model  implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_actividades_fisicas';
    protected $fillable = [
        'nombre_actividad',
        'tiempo',
        'actividable_id',
        'actividable_type'
    ];

    // Relación polimorfica
    public function actividable()
    {
        return $this->morphTo();
    }
}
