<?php

namespace App\Models\Medico;

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
 * App\Models\Medico\AntecedenteClinico
 *
 * @property int $id
 * @property string $descripcion
 * @property int $antecedentable_id
 * @property string $antecedentable_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent $antecedentable
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|AntecedenteClinico newModelQuery()
 * @method static Builder|AntecedenteClinico newQuery()
 * @method static Builder|AntecedenteClinico query()
 * @method static Builder|AntecedenteClinico whereAntecedentableId($value)
 * @method static Builder|AntecedenteClinico whereAntecedentableType($value)
 * @method static Builder|AntecedenteClinico whereCreatedAt($value)
 * @method static Builder|AntecedenteClinico whereDescripcion($value)
 * @method static Builder|AntecedenteClinico whereId($value)
 * @method static Builder|AntecedenteClinico whereUpdatedAt($value)
 * @mixin Eloquent
 */
class AntecedenteClinico extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table  = 'med_antecedentes_clinicos';
    protected $fillable = [
        'descripcion',
        'antecedentable_id',
        'antecedentable_type',
    ];
    private static array $whiteListFilter = ['*'];

    // Relación polimorfica
    public function antecedentable()
    {
        return $this->morphTo();
    }
}
