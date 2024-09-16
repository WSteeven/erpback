<?php

namespace App\Models;

use App\Http\Resources\DetalleProductoResource;
use App\Models\ComprasProveedores\OrdenCompra;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Producto
 *
 * @method static whereIn(string $string, mixed $categorias)
 * @property int $id
 * @property string $nombre
 * @property int $categoria_id
 * @property int $unidad_medida_id
 * @property string $tipo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Categoria|null $categoria
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cliente> $clientes
 * @property-read int|null $clientes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DetalleProducto> $detalles
 * @property-read int|null $detalles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, OrdenCompra> $productoOrdenCompra
 * @property-read int|null $producto_orden_compra_count
 * @property-read \App\Models\UnidadMedida|null $unidadMedida
 * @method static \Illuminate\Database\Eloquent\Builder|Producto acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Producto filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Producto ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Producto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Producto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Producto query()
 * @method static \Illuminate\Database\Eloquent\Builder|Producto setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Producto setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Producto setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Producto whereCategoriaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Producto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Producto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Producto whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Producto whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Producto whereUnidadMedidaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Producto whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Producto extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, Searchable;
    use AuditableModel;

    protected $table = "productos";

    protected $fillable = ["nombre", "categoria_id", "unidad_medida_id", 'tipo'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    const BIEN = 'BIEN';
    const SERVICIO = 'SERVICIO';

    const MATERIAL = 7;
    const EQUIPO = 4;

    private static $whiteListFilter = [
        '*',
    ];

    public function toSearchableArray()
    {
        return [
            'nombre' => $this->nombre,
        ];
    }

    public static function cantidadDetalles($id)
    {
        $detalles = DetalleProducto::where('producto_id', $id)->get();
        $result = count($detalles);
        return $result;
    }

    /**
     * La función "obtenerProductoPorNombre" recupera un producto por su nombre de la base de datos.
     *
     * @param nombre El parámetro "nombre" es una cadena que representa el nombre del producto a buscar.
     *
     * @return una única instancia del modelo "Producto" que coincide con el nombre dado.
     */
    public static function obtenerProductoPorNombre($nombre)
    {
        return Producto::where('nombre', $nombre)->first();
    }

    /**
     * Relacion uno a muchos
     * Un producto tiene varios detalles
     */
    public function detalles()
    {
        return $this->hasMany(DetalleProducto::class);
    }


    //Un producto tiene varios codigos de cliente
    public function clientes()
    {
        return $this->belongsToMany(Cliente::class);
    }

    /**
     * Uno o varios productos pertenecen a una categoría
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    /**
     * Uno o varios productos pertenecen a una categoría
     */
    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedida::class);
    }

    /**
     * Relación muchos a muchos.
     * Uno o varios productos estan en una orden de compra.
     */
    public function productoOrdenCompra()
    {
        return $this->belongsToMany(OrdenCompra::class, 'cmp_item_detalle_orden_compra', 'orden_compra_id', 'producto_id')
            ->withPivot(['descripcion', 'cantidad', 'porcentaje_descuento', 'facturable', 'grava_iva', 'precio_unitario', 'iva', 'subtotal', 'total'])->withTimestamps();
    }
}
