<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Medico\AntecedenteFamiliar
 *
 * @property int $id
 * @property string $descripcion
 * @property int $tipo_antecedente_familiar_id
 * @property string $parentesco
 * @property int $antecedentable_id
 * @property string $antecedentable_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $antecedentable
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Medico\TipoAntecedenteFamiliar|null $tipoAntecedenteFamiliar
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteFamiliar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteFamiliar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteFamiliar query()
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteFamiliar whereAntecedentableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteFamiliar whereAntecedentableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteFamiliar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteFamiliar whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteFamiliar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteFamiliar whereParentesco($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteFamiliar whereTipoAntecedenteFamiliarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteFamiliar whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AntecedenteFamiliar extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_antecedentes_familiares';
    protected $fillable = [
        'descripcion',
        'tipo_antecedente_familiar_id',
        'parentesco',
        'antecedentable_id',
        'antecedentable_type',
    ];
    public function tipoAntecedenteFamiliar()
    {
        return $this->hasOne(TipoAntecedenteFamiliar::class);
    }
    public function antecedentable()
    {
        return $this->morphTo();
    }
}
