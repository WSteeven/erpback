<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UppercaseValuesTrait;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
/**
 * App\Models\TipoElemento
 *
 * @property int $id
 * @property string $descripcion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|TipoElemento newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoElemento newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoElemento query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoElemento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoElemento whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoElemento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoElemento whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TipoElemento extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;

    protected $table = "tipos_elementos";
    protected $fillable = [
        'nombre',
    ];
}
