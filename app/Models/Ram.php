<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Ram
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\ComputadoraTelefono|null $computadoraTelefono
 * @method static \Illuminate\Database\Eloquent\Builder|Ram newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ram newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ram query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ram whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ram whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ram whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ram whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Ram extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    protected $table = 'rams';
    protected $fillable = ['nombre'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    /**
     * Relación uno a muchos.
     * Una ram esta en todas las computadoras y telefonos.
     */
    public function computadoraTelefono(){
        return $this->hasOne(ComputadoraTelefono::class);
    }
}

