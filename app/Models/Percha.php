<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Percha
 *
 * @property int $id
 * @property string $nombre
 * @property int $sucursal_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Sucursal|null $sucursal
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ubicacion> $ubicaciones
 * @property-read int|null $ubicaciones_count
 * @method static \Illuminate\Database\Eloquent\Builder|Percha newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Percha newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Percha query()
 * @method static \Illuminate\Database\Eloquent\Builder|Percha whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Percha whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Percha whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Percha whereSucursalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Percha whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Percha extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    
    protected $table = 'perchas';
    protected $fillable = ['nombre', 'sucursal_id'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];



    /**
     * Relacion uno a muchos (inversa)
     * Una o varias perchas pertenecen a una sucursal
     */
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    /* public function pisos()
    {
        return $this->belongsToMany(Piso::class);
    } */

    /**
     * Relacion uno a muchos
     * Una percha tiene muchas ubicaciones
     */
    public function ubicaciones()
    {
        return $this->hasMany(Ubicacion::class);
    }
}
