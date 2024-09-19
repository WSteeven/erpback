<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use App\Traits\UppercaseValuesTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\RecursosHumanos\SeleccionContratacion\FormacionAcademica
 *
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Model|Eloquent $formacionable
 * @method static Builder|FormacionAcademica newModelQuery()
 * @method static Builder|FormacionAcademica newQuery()
 * @method static Builder|FormacionAcademica query()
 * @property int $id
 * @property string $nivel
 * @property string $nombre
 * @property int $formacionable_id
 * @property string $formacionable_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|FormacionAcademica whereCreatedAt($value)
 * @method static Builder|FormacionAcademica whereFormacionableId($value)
 * @method static Builder|FormacionAcademica whereFormacionableType($value)
 * @method static Builder|FormacionAcademica whereId($value)
 * @method static Builder|FormacionAcademica whereNivel($value)
 * @method static Builder|FormacionAcademica whereNombre($value)
 * @method static Builder|FormacionAcademica whereUpdatedAt($value)
 * @mixin Eloquent
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
