<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Autorizacion
 *
 * @property int $id
 * @property string $nombre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Pedido|null $pedido
 * @property-read TransaccionBodega|null $transaccion
 * @property-read Transferencia|null $transferencia
 * @method static Builder|Autorizacion acceptRequest(?array $request = null)
 * @method static Builder|Autorizacion filter(?array $request = null)
 * @method static Builder|Autorizacion ignoreRequest(?array $request = null)
 * @method static Builder|Autorizacion newModelQuery()
 * @method static Builder|Autorizacion newQuery()
 * @method static Builder|Autorizacion query()
 * @method static Builder|Autorizacion setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Autorizacion setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Autorizacion setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Autorizacion whereCreatedAt($value)
 * @method static Builder|Autorizacion whereId($value)
 * @method static Builder|Autorizacion whereNombre($value)
 * @method static Builder|Autorizacion whereUpdatedAt($value)
 * @mixin Eloquent
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
    const VALIDADO = 'VALIDADO';
    const ANULADO = 'ANULADO';

    const PENDIENTE_ID = 1;
    const APROBADO_ID = 2;
    const CANCELADO_ID = 3;
    const VALIDADO_ID = 4;

    private static array $whiteListFilter = [
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
