<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Medico\AntecedenteFamiliar
 *
 * @property int $id
 * @property string $descripcion
 * @property int $tipo_antecedente_familiar_id
 * @property string $parentesco
 * @property int $antecedentable_id
 * @property string $antecedentable_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent $antecedentable
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read TipoAntecedenteFamiliar|null $tipoAntecedenteFamiliar
 * @method static Builder|AntecedenteFamiliar newModelQuery()
 * @method static Builder|AntecedenteFamiliar newQuery()
 * @method static Builder|AntecedenteFamiliar query()
 * @method static Builder|AntecedenteFamiliar whereAntecedentableId($value)
 * @method static Builder|AntecedenteFamiliar whereAntecedentableType($value)
 * @method static Builder|AntecedenteFamiliar whereCreatedAt($value)
 * @method static Builder|AntecedenteFamiliar whereDescripcion($value)
 * @method static Builder|AntecedenteFamiliar whereId($value)
 * @method static Builder|AntecedenteFamiliar whereParentesco($value)
 * @method static Builder|AntecedenteFamiliar whereTipoAntecedenteFamiliarId($value)
 * @method static Builder|AntecedenteFamiliar whereUpdatedAt($value)
 * @mixin Eloquent
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
