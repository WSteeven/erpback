<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Fibra
 *
 * @property int $detalle_id
 * @property int $span_id
 * @property int $tipo_fibra_id
 * @property int $hilo_id
 * @property int $punta_inicial
 * @property int $punta_final
 * @property int $custodia
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\DetalleProducto|null $detalle
 * @property-read \App\Models\Hilo|null $hilo
 * @property-read \App\Models\Span|null $span
 * @property-read \App\Models\TipoFibra|null $tipo_fibra
 * @method static \Illuminate\Database\Eloquent\Builder|Fibra newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Fibra newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Fibra query()
 * @method static \Illuminate\Database\Eloquent\Builder|Fibra whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fibra whereCustodia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fibra whereDetalleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fibra whereHiloId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fibra wherePuntaFinal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fibra wherePuntaInicial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fibra whereSpanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fibra whereTipoFibraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fibra whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Fibra extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    
    protected $table = 'fibras';
    protected $fillable = [
        'detalle_id',
        'span_id',
        'tipo_fibra_id',
        'hilo_id',
        'punta_inicial',
        'punta_final',
        'custodia',
    ];

    public function getKeyName()
    {
        return 'detalle_id';
    }

    /**
     * Relacion uno a uno (inversa).
     * Una fibra es un detalle de producto
     */
    public function detalle()
    {
        return $this->belongsTo(DetalleProducto::class, 'detalle_id');
    }

    /**
     * Relacion uno a uno (inversa).
     * Una fibra tiene 1 span
     */
    public function span()
    {
        return $this->belongsTo(Span::class);
    }

    /**
     * Relacion uno a uno (inversa).
     * Una fibra tiene 1 tipo de fibra
     */
    public function tipo_fibra()
    {
        return $this->belongsTo(TipoFibra::class);
    }

    /**
     * Relacion uno a uno (inversa).
     * Una fibra tiene 1 hilo
     */
    public function hilo()
    {
        return $this->belongsTo(Hilo::class);
    }
}
