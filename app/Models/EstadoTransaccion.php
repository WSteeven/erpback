<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\EstadoTransaccion
 *
 * @method static where(string $string, string $PENDIENTE)
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Pedido|null $pedido
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TransaccionBodega> $transacciones
 * @property-read int|null $transacciones_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Traspaso> $traspasos
 * @property-read int|null $traspasos_count
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoTransaccion acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoTransaccion filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoTransaccion ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoTransaccion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoTransaccion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoTransaccion query()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoTransaccion setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoTransaccion setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoTransaccion setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoTransaccion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoTransaccion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoTransaccion whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoTransaccion whereUpdatedAt($value)
 * @mixin \Eloquent
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

    private static $whiteListFilter = [
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
