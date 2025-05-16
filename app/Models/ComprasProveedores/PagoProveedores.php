<?php

namespace App\Models\ComprasProveedores;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\ComprasProveedores\PagoProveedores
 *
 * @property int $id
 * @property string $nombre
 * @property int|null $realizador_id
 * @property bool $estado_bloqueado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ComprasProveedores\ItemPagoProveedores> $items
 * @property-read int|null $items_count
 * @property-read Empleado|null $realizador
 * @method static \Illuminate\Database\Eloquent\Builder|PagoProveedores acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PagoProveedores filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PagoProveedores ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PagoProveedores newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PagoProveedores newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PagoProveedores query()
 * @method static \Illuminate\Database\Eloquent\Builder|PagoProveedores setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PagoProveedores setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PagoProveedores setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|PagoProveedores whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PagoProveedores whereEstadoBloqueado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PagoProveedores whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PagoProveedores whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PagoProveedores whereRealizadorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PagoProveedores whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PagoProveedores extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;
    public $table = 'cmp_pagos_proveedores';
    public $fillable = [
        'nombre',
        'realizador_id',
        'estado_bloqueado',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'estado_bloqueado' => 'boolean',
    ];

    private static $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

    public function realizador()
    {
        return $this->belongsTo(Empleado::class, 'realizador_id', 'id');
    }
    public function items()
    {
        return $this->hasMany(ItemPagoProveedores::class, 'pago_proveedor_id', 'id');
    }

}
