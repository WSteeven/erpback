<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Medico\TipoExamen
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|TipoExamen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoExamen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoExamen query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoExamen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoExamen whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoExamen whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoExamen whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TipoExamen extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_tipos_examenes';
    protected $fillable = [
        'nombre',
    ];
}
