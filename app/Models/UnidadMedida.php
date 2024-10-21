<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\UnidadMedida
 *
 * @property int $id
 * @property string $nombre
 * @property string $simbolo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Producto> $productos
 * @property-read int|null $productos_count
 * @method static \Illuminate\Database\Eloquent\Builder|UnidadMedida newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UnidadMedida newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UnidadMedida query()
 * @method static \Illuminate\Database\Eloquent\Builder|UnidadMedida whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnidadMedida whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnidadMedida whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnidadMedida whereSimbolo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnidadMedida whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UnidadMedida extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;

    protected $table = 'unidades_medidas';
    protected $fillable =[
        'nombre',
        'simbolo',
    ];

    /**
     * Relacion uno a muchos.
     * Una unidad de medida está en varios productos
     */
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

}
