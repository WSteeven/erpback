<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\TipoFibra
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Fibra|null $fibra
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFibra newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFibra newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFibra query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFibra whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFibra whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFibra whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoFibra whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TipoFibra extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;

    protected $table = 'tipo_fibras';
    protected $fillable = ['nombre'];


    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    /**
     * Relacion uno a uno.
     * Un tipo de fibra esta en 1 fibra
     */
    public function fibra(){
        return $this->hasOne(Fibra::class);
    }
}
