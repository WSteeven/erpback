<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Autorizacion
 *
 * @method static where(string $string, string $PENDIENTE)
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Pedido|null $pedido
 * @property-read \App\Models\TransaccionBodega|null $transaccion
 * @property-read \App\Models\Transferencia|null $transferencia
 * @method static \Illuminate\Database\Eloquent\Builder|Autorizacion acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Autorizacion filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Autorizacion ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Autorizacion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Autorizacion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Autorizacion query()
 * @method static \Illuminate\Database\Eloquent\Builder|Autorizacion setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Autorizacion setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Autorizacion setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Autorizacion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Autorizacion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Autorizacion whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Autorizacion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Autorizacion extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable;
    use AuditableModel;

    protected $table = "autorizaciones";
    protected $fillable = ["nombre"];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    const PENDIENTE = 'PENDIENTE';
    const APROBADO = 'APROBADO';
    const CANCELADO = 'CANCELADO';

    const PENDIENTE_ID = 1;
    const APROBADO_ID = 2;
    const CANCELADO_ID = 3;

    private static $whiteListFilter = [
        '*',
    ];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

    /**
     * Relacion muchos a muchos
     * Obtener las transacciones que pertenecen a la autorizacion
     */
    /* public function transacciones()
    {
        return $this->belongsToMany(Autorizacion::class, 'tiempo_autorizacion_transaccion', 'transaccion_id', 'autorizacion_id')
            ->withPivot('observacion')
            ->withTimestamps()
            ->orderByPivot('created_at', 'desc');
    } */

    /**
     * Relación uno a muchos.
     * Una autorizacion esta en varias transacciones.
     */
    public function transaccion(){
        return $this->hasOne(TransaccionBodega::class);
    }

    /**
     * Relación uno a uno.
     * Una autorización puede estar en un pedido a la vez.
     */
    public function pedido()
    {
        return $this->hasOne(Pedido::class);
    }
    /**
     * Relación uno a uno.
     * Una autorizacion puede estar en una transferencia a la vez.
     */
    public function transferencia()
    {
        return $this->hasOne(Transferencia::class);
    }
}
