<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Medico\EstadoExamen
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoExamen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoExamen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoExamen query()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoExamen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoExamen whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoExamen whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoExamen whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EstadoExamen extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    const SOLICITADO_ID = 1;

    protected $table = 'med_estados_examenes';
    protected $fillable = [
        'nombre',
    ];
}
