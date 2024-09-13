<?php

namespace App\Models;

use App\Models\ComprasProveedores\Prefactura;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Cliente
 *
 * @property int $id
 * @property int $empresa_id
 * @property int $parroquia_id
 * @property bool $requiere_bodega
 * @property bool $estado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $logo_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\CodigoCliente|null $codigos
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ControlStock> $controlStock
 * @property-read int|null $control_stock_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DetalleProducto> $detalles
 * @property-read int|null $detalles_count
 * @property-read \App\Models\Empresa $empresa
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Inventario> $inventarios
 * @property-read int|null $inventarios_count
 * @property-read \App\Models\Parroquia $parroquia
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Prefactura> $prefacturas
 * @property-read int|null $prefacturas_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sucursal> $sucursales
 * @property-read int|null $sucursales_count
 * @method static \Illuminate\Database\Eloquent\Builder|Cliente acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Cliente filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Cliente ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Cliente newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cliente newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cliente query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cliente setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Cliente setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Cliente setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Cliente whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cliente whereEmpresaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cliente whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cliente whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cliente whereLogoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cliente whereParroquiaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cliente whereRequiereBodega($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cliente whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Cliente extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    use Filterable;

    protected $table = "clientes";
    protected $fillable = ['empresa_id', 'parroquia_id', 'requiere_bodega', 'estado', 'logo_url'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'requiere_bodega' => 'boolean',
        'estado' => 'boolean'
    ];


    const JEANPATRICIO = 1;
    const JPCONSTRUCRED = 5;

    private static $whiteListFilter = ['*'];

    public function sucursales()
    {
        return $this->hasMany(Sucursal::class);
    }
    public function parroquia()
    {
        return $this->belongsTo(Parroquia::class);
    }

    public function prefacturas()
    {
        return $this->hasMany(Prefactura::class, 'cliente_id');
    }

    /**
     * Relacion uno a uno (inversa)
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }
    /**
     * Relacion uno a muchos
     * Un cliente tiene varios codigos para varios productos
     */
    public function codigos()
    {
        return $this->hasOne(CodigoCliente::class);
    }

    /**
     * Relacion uno a muchos
     * Un cliente es propietario de muchos items del inventario
     */
    public function inventarios()
    {
        return $this->hasMany(Inventario::class);
    }

    /**
     * Relacion muchos a muchos.
     * Un cliente tiene varios detalles_productos en inventario.
     */
    public function detalles()
    {
        return $this->belongsToMany(DetalleProducto::class, 'inventarios', 'cliente_id', 'detalle_id');
    }

    /**
     * Relación uno a muchos.
     * Un cliente tiene un producto al que se le hace un control de stock para estar pendiente de su reabastecimiento
     */
    public function controlStock()
    {
        return $this->hasMany(ControlStock::class);
    }
}
