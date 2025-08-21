<?php

namespace App\Models;

use App\Models\ComprasProveedores\Prefactura;
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
 * App\Models\Cliente
 *
 * @property int $id
 * @property int $empresa_id
 * @property int $parroquia_id
 * @property bool $requiere_bodega
 * @property bool $estado
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $logo_url
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read CodigoCliente|null $codigos
 * @property-read Collection<int, ControlStock> $controlStock
 * @property-read int|null $control_stock_count
 * @property-read Collection<int, DetalleProducto> $detalles
 * @property-read int|null $detalles_count
 * @property-read Empresa $empresa
 * @property-read Collection<int, Inventario> $inventarios
 * @property-read int|null $inventarios_count
 * @property-read Parroquia $parroquia
 * @property-read Collection<int, Prefactura> $prefacturas
 * @property-read int|null $prefacturas_count
 * @property-read Collection<int, Sucursal> $sucursales
 * @property-read int|null $sucursales_count
 * @method static Builder|Cliente acceptRequest(?array $request = null)
 * @method static Builder|Cliente filter(?array $request = null)
 * @method static Builder|Cliente ignoreRequest(?array $request = null)
 * @method static Builder|Cliente newModelQuery()
 * @method static Builder|Cliente newQuery()
 * @method static Builder|Cliente query()
 * @method static Builder|Cliente setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Cliente setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Cliente setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Cliente whereCreatedAt($value)
 * @method static Builder|Cliente whereEmpresaId($value)
 * @method static Builder|Cliente whereEstado($value)
 * @method static Builder|Cliente whereId($value)
 * @method static Builder|Cliente whereLogoUrl($value)
 * @method static Builder|Cliente whereParroquiaId($value)
 * @method static Builder|Cliente whereRequiereBodega($value)
 * @method static Builder|Cliente whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Cliente extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    use Filterable;

    protected $table = "clientes";
    protected $fillable = ['empresa_id', 'parroquia_id', 'requiere_bodega', 'requiere_fr', 'estado', 'logo_url'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'requiere_bodega' => 'boolean',
        'requiere_fr' => 'boolean',
        'estado' => 'boolean'
    ];


    const JEANPATRICIO = 1;
    const JPCONSTRUCRED = 5;

    private static array $whiteListFilter = ['*'];

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
