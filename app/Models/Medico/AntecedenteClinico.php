<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Medico\AntecedenteClinico
 *
 * @property int $id
 * @property string $descripcion
 * @property int $antecedentable_id
 * @property string $antecedentable_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $antecedentable
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteClinico newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteClinico newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteClinico query()
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteClinico whereAntecedentableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteClinico whereAntecedentableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteClinico whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteClinico whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteClinico whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AntecedenteClinico whereUpdatedAt($value)
 * @mixin \Eloquent
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
    private static $whiteListFilter = ['*'];

    // Relación polimorfica
    public function antecedentable()
    {
        return $this->morphTo();
    }
}
