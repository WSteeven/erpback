<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\CodigoCliente
 *
 * @property int $id
 * @property int $cliente_id
 * @property string|null $nombre_cliente
 * @property int $detalle_id
 * @property string $codigo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cliente $cliente
 * @property-read \App\Models\DetalleProducto $detalle
 * @method static \Illuminate\Database\Eloquent\Builder|CodigoCliente acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CodigoCliente filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CodigoCliente ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CodigoCliente newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CodigoCliente newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CodigoCliente query()
 * @method static \Illuminate\Database\Eloquent\Builder|CodigoCliente setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CodigoCliente setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CodigoCliente setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|CodigoCliente whereClienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CodigoCliente whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CodigoCliente whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CodigoCliente whereDetalleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CodigoCliente whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CodigoCliente whereNombreCliente($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CodigoCliente whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CodigoCliente extends Model // implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable;
    // use AuditableModel;

    protected $table = "codigo_cliente";
    protected $fillable = ['nombre_cliente','cliente_id', 'detalle_id', 'codigo'];
    
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];
    
    private static $whiteListFilter = [
        '*',
    ];
    
    /**
     * Relacion uno a muchos (inversa)
     * Un codigo pertenece a un detalle
     */
    public function detalle(){
        return $this->belongsTo(DetalleProducto::class);
    }
    /**
     * Relacion uno a muchos (inversa)
     * Un cliente tiene varios codigos para varios productos
     */
    public function cliente(){
        return $this->belongsTo(Cliente::class);
    }
}
