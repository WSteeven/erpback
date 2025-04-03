<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Procesador
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\ComputadoraTelefono|null $computadoraTelefono
 * @method static \Illuminate\Database\Eloquent\Builder|Procesador newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Procesador newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Procesador query()
 * @method static \Illuminate\Database\Eloquent\Builder|Procesador whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Procesador whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Procesador whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Procesador whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Procesador extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    protected $table = 'procesadores';
    protected $fillable = ['nombre'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    /**
     * Relación uno a muchos.
     * Un procesador esta en todas las computadoras y telefonos.
     */
    public function computadoraTelefono(){
        return $this->hasOne(ComputadoraTelefono::class);
    }
}
