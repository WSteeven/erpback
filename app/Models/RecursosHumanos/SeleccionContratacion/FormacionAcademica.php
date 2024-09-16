<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\RecursosHumanos\SeleccionContratacion\FormacionAcademica
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Model|\Eloquent $formacionable
 * @method static \Illuminate\Database\Eloquent\Builder|FormacionAcademica newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FormacionAcademica newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FormacionAcademica query()
 * @mixin \Eloquent
 */
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
