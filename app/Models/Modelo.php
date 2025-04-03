<?php

namespace App\Models;

use App\Models\Vehiculos\Vehiculo;
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
 * App\Models\Modelo
 *
 * @property int $id
 * @property string $nombre
 * @property int $marca_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Marca|null $marca
 * @property-read Producto|null $producto
 * @method static Builder|Modelo newModelQuery()
 * @method static Builder|Modelo newQuery()
 * @method static Builder|Modelo query()
 * @method static Builder|Modelo whereCreatedAt($value)
 * @method static Builder|Modelo whereId($value)
 * @method static Builder|Modelo whereMarcaId($value)
 * @method static Builder|Modelo whereNombre($value)
 * @method static Builder|Modelo whereUpdatedAt($value)
 * @property-read Vehiculo|null $vehiculos
 * @mixin Eloquent
 */
class Modelo extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;

    protected $table = 'modelos';
    protected $fillable = [
        'nombre',
        'marca_id'
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    /**
     * Relacion uno a uno
     * Un modelo esta en un producto
     */
    public function producto()
    {
        return $this->hasOne(Producto::class);
    }

    /**
     * Relacion uno a muchos (inversa)
     * Uno o varios modelos pertenecen a una marca
     *  */
    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    /**
     * Relación uno a uno.
     * Un modelo está en varios vehículos.
     */
    public function vehiculos(){
        return $this->hasOne(Vehiculo::class);
    }
}
