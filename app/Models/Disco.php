<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Disco
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\ComputadoraTelefono|null $computadoraTelefono
 * @method static \Illuminate\Database\Eloquent\Builder|Disco newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Disco newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Disco query()
 * @method static \Illuminate\Database\Eloquent\Builder|Disco whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Disco whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Disco whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Disco whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Disco extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;

    
    protected $table = 'discos';
    protected $fillable = ['nombre'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    /**
     * Relación uno a muchos.
     * Un disco SSD o HDD esta en todas las computadoras y telefonos.
     */
    public function computadoraTelefono(){
        return $this->hasOne(ComputadoraTelefono::class);
    }
}
