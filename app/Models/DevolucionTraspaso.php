<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\DevolucionTraspaso
 *
 * @property int $id
 * @property int $detalle_inventario_traspaso_id
 * @property int $cantidad
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\DetalleInventarioTraspaso|null $detalleInventarioTraspaso
 * @method static \Illuminate\Database\Eloquent\Builder|DevolucionTraspaso newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DevolucionTraspaso newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DevolucionTraspaso query()
 * @method static \Illuminate\Database\Eloquent\Builder|DevolucionTraspaso whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DevolucionTraspaso whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DevolucionTraspaso whereDetalleInventarioTraspasoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DevolucionTraspaso whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DevolucionTraspaso whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DevolucionTraspaso extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;

    protected $table = 'devolucion_traspasos';
    protected $fillable = [
        'detalle_inventario_traspaso_id',
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
    public function detalleInventarioTraspaso()
    {
        return $this->belongsTo(DetalleInventarioTraspaso::class);
    }
}
