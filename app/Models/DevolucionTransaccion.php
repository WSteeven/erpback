<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\DevolucionTransaccion
 *
 * @property int $id
 * @property int $detalle_producto_transaccion_id
 * @property int $cantidad
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\DetalleProductoTransaccion|null $detalleProductoTransaccion
 * @method static \Illuminate\Database\Eloquent\Builder|DevolucionTransaccion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DevolucionTransaccion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DevolucionTransaccion query()
 * @method static \Illuminate\Database\Eloquent\Builder|DevolucionTransaccion whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DevolucionTransaccion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DevolucionTransaccion whereDetalleProductoTransaccionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DevolucionTransaccion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DevolucionTransaccion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DevolucionTransaccion extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    protected $table = 'devoluciones_transacciones';
    protected $fillable = [
        'detalle_producto_transaccion_id',
        'cantidad',
    ];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */


    /**
     * Relación uno a muchos(inversa).
     * Una o más devoluciones se hacen para un item de un traspaso.
     */
    public function detalleProductoTransaccion()
    {
        return $this->belongsTo(DetalleProductoTransaccion::class);
    }
}
