<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Medico\EstadoCitaMedica
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoCitaMedica newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoCitaMedica newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoCitaMedica query()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoCitaMedica whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoCitaMedica whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoCitaMedica whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoCitaMedica whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EstadoCitaMedica extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_estados_citas_medicas';
    protected $fillable = [
        'nombre',
    ];
}
