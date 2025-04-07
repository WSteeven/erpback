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
 * App\Models\EstadoTransaccion
 *
 * @method static where(string $string, string $PENDIENTE)
 * @property int $id
 * @property string $nombre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Pedido|null $pedido
 * @property-read Collection<int, TransaccionBodega> $transacciones
 * @property-read int|null $transacciones_count
 * @property-read Collection<int, Traspaso> $traspasos
 * @property-read int|null $traspasos_count
 * @method static Builder|EstadoTransaccion acceptRequest(?array $request = null)
 * @method static Builder|EstadoTransaccion filter(?array $request = null)
 * @method static Builder|EstadoTransaccion ignoreRequest(?array $request = null)
 * @method static Builder|EstadoTransaccion newModelQuery()
 * @method static Builder|EstadoTransaccion newQuery()
 * @method static Builder|EstadoTransaccion query()
 * @method static Builder|EstadoTransaccion setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|EstadoTransaccion setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|EstadoTransaccion setLoadInjectedDetection($load_default_detection)
 * @method static Builder|EstadoTransaccion whereCreatedAt($value)
 * @method static Builder|EstadoTransaccion whereId($value)
 * @method static Builder|EstadoTransaccion whereNombre($value)
 * @method static Builder|EstadoTransaccion whereUpdatedAt($value)
 * @mixin Eloquent
 */
class EstadoTransaccion extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable;
    use AuditableModel;

    protected $table = 'estados_transacciones_bodega';
    protected $fillable=['nombre'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];
    const PENDIENTE ='PENDIENTE';
    const COMPLETA ='COMPLETA';
    const PARCIAL ='PARCIAL';
    const ANULADA ='NO REALIZADA';

    const PENDIENTE_ID = 1;

    private static array $whiteListFilter = [
        '*',
    ];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

    /* public function transacciones()
    {
        return $this->belongsToMany(TransaccionBodega::class, 'tiempo_estado_transaccion','transaccion_id', 'estado_id')
            ->withPivot('observacion')
            ->withTimestamps()
            ->orderByPivot('created_at', 'desc');
    } */

    /**
     * Relación uno a muchos.
     * Un estado esta en varias transacciones.
     */
    public function transacciones(){
        return $this->hasMany(TransaccionBodega::class);
    }

    /**
     * Relacion uno a muchos.
     * Un estado esta en varios traspasos
     */
    public function traspasos(){
        return $this->hasMany(Traspaso::class);
    }

    /**
     * Relación uno a uno.
     * Un estado puede estar en un pedido a la vez.
     */
    public function pedido(){
        return $this->hasOne(Pedido::class);
    }




}
