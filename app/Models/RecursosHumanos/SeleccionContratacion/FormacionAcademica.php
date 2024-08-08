<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class FormacionAcademica extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;

    protected $table = 'rrhh_contratacion_formaciones_academicas';
    protected $fillable = [
        'nivel',
        'nombre',
        'formacionable_id',
        'formacionable_type',
    ];

    public function formacionable()
    {
        return $this->morphTo();
    }
}
