<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Medico\Medicacion
 *
 * @property int $id
 * @property string $nombre
 * @property string $cantidad
 * @property int $medicable_id
 * @property string $medicable_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Model|\Eloquent $medicable
 * @method static \Illuminate\Database\Eloquent\Builder|Medicacion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Medicacion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Medicacion query()
 * @method static \Illuminate\Database\Eloquent\Builder|Medicacion whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Medicacion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Medicacion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Medicacion whereMedicableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Medicacion whereMedicableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Medicacion whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Medicacion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Medicacion extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_medicaciones';
    protected $fillable = [
        'nombre',
        'cantidad',
        'medicable_id',
        'medicable_type',
    ];
    public function medicable()
    {
        return $this->morphTo();
    }
}
