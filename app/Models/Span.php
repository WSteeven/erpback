<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Span
 *
 * @property int $id
 * @property int $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Fibra|null $fibra
 * @method static \Illuminate\Database\Eloquent\Builder|Span newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Span newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Span query()
 * @method static \Illuminate\Database\Eloquent\Builder|Span whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Span whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Span whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Span whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Span extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;

    protected $table = 'spans';
    protected $fillable = ['nombre'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    /**
     * Relación uno a uno.
     * Un span debe estar en una fibra
     */
    public function fibra()
    {
        return $this->hasOne(Fibra::class);
    }
}
