<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * App\Models\Medico\DescripcionAntecedenteTrabajo
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Medico\FichaPreocupacional|null $fichaPreocupacional
 * @method static \Illuminate\Database\Eloquent\Builder|DescripcionAntecedenteTrabajo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DescripcionAntecedenteTrabajo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DescripcionAntecedenteTrabajo query()
 * @mixin \Eloquent
 */
class DescripcionAntecedenteTrabajo extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_descripciones_antecedentes_trabajos';
    protected $fillable = [
        'calificado_iss',
        'descripcion',
        'fecha',
        'observacion',
        'tipo_descripcion_antecedente_trabajo',
        'ficha_preocupacional_id'
    ];

    protected $casts = [
        'calificado_iss' => 'bool',
    ];

    public function fichaPreocupacional()
    {
        return $this->hasOne(FichaPreocupacional::class, 'id', 'ficha_preocupacional_id');
    }
}


/**
 * Esta tabla no se usa ni ningun elemento relacionado
 */
