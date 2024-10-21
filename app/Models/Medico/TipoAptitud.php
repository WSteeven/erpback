<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Medico\TipoAptitud
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAptitud newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAptitud newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAptitud query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAptitud whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAptitud whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAptitud whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoAptitud whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TipoAptitud extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_tipos_aptitudes';
    protected $fillable = [
        'nombre',
    ];
}
