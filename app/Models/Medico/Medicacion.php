<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Medico\Medicacion
 *
 * @property int $id
 * @property string $nombre
 * @property string $cantidad
 * @property int $medicable_id
 * @property string $medicable_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Model|Eloquent $medicable
 * @method static Builder|Medicacion newModelQuery()
 * @method static Builder|Medicacion newQuery()
 * @method static Builder|Medicacion query()
 * @method static Builder|Medicacion whereCantidad($value)
 * @method static Builder|Medicacion whereCreatedAt($value)
 * @method static Builder|Medicacion whereId($value)
 * @method static Builder|Medicacion whereMedicableId($value)
 * @method static Builder|Medicacion whereMedicableType($value)
 * @method static Builder|Medicacion whereNombre($value)
 * @method static Builder|Medicacion whereUpdatedAt($value)
 * @mixin Eloquent
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
