<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Comprobante
 *
 * @property int $transaccion_id
 * @property int $firmada
 * @property string $estado
 * @property string|null $observacion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\TransaccionBodega $transaccion
 * @method static \Illuminate\Database\Eloquent\Builder|Comprobante newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comprobante newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comprobante query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comprobante whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comprobante whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comprobante whereFirmada($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comprobante whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comprobante whereTransaccionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comprobante whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Comprobante extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    protected $table = 'comprobantes';
    protected $fillable = [
        'transaccion_id',
        'firmada',
        'estado',
        'observacion',
    ];

    //obtener la llave primaria
    public function getKeyName()
    {
        return 'transaccion_id';
    }

    /**
     * Relación uno a uno.
     * Un comprobante se emite para un egreso.
     */
    public function transaccion()
    {
        return $this->belongsTo(TransaccionBodega::class, 'transaccion_id');
    }

    /**
     * La función `verificarEgresoCompletado` verifica si todos los productos de una transacción se han
     * recibido en su totalidad.
     * 
     * @param int $transaccion_id Representa el ID de una transacción. La función recupera detalles de los
     * productos relacionados con la transacción del modelo `DetalleProductoTransaccion` y verifica si
     * la cantidad recibida de cada producto coincide con la cantidad inicial.
     * 
     * @return bool Si todos los registros tienen los mismos valores en cantidad_inicial y recibido, 
     * devuelve `true`, indicando que el egreso (transacción) se completó.
     */
    public static function verificarEgresoCompletado(int $transaccion_id)
    {
        $detalles = DetalleProductoTransaccion::where('transaccion_id', $transaccion_id)->get();
        return $detalles->every(function ($detalle) {
            return $detalle->cantidad_inicial === $detalle->recibido;
        });
    }
}
