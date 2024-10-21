<?php

namespace App\Models;

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
 * App\Models\Hilo
 *
 * @property int $id
 * @property string $nombre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|Hilo newModelQuery()
 * @method static Builder|Hilo newQuery()
 * @method static Builder|Hilo query()
 * @method static Builder|Hilo whereCreatedAt($value)
 * @method static Builder|Hilo whereId($value)
 * @method static Builder|Hilo whereNombre($value)
 * @method static Builder|Hilo whereUpdatedAt($value)
 * @property-read \App\Models\DetalleProducto|null $detalle
 * @mixin Eloquent
 */
class Hilo extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;

    protected $fillable=["nombre"];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    public function detalle(){
        return $this->hasOne(DetalleProducto::class);
    }

}
