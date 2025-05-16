<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Medico\CaracteristicaExamen
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|CaracteristicaExamen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CaracteristicaExamen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CaracteristicaExamen query()
 * @mixin \Eloquent
 */
class CaracteristicaExamen extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_caracteristicas_examenes';
    protected $fillable = [
        'nombre',
    ];
}
