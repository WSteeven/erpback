<?php

namespace App\Models;

use App\Models\ComprasProveedores\OrdenCompra;
use App\Traits\UppercaseValuesTrait;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Producto
 *
 * @method cantidadDetalles(mixed $id)
 * @method static whereIn(string $string, mixed $categorias)
 * @property int $id
 * @property string $nombre
 * @property int $categoria_id
 * @property int $unidad_medida_id
 * @property string $tipo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Categoria|null $categoria
 * @property-read Collection<int, Cliente> $clientes
 * @property-read int|null $clientes_count
 * @property-read Collection<int, DetalleProducto> $detalles
 * @property-read int|null $detalles_count
 * @property-read Collection<int, OrdenCompra> $productoOrdenCompra
 * @property-read int|null $producto_orden_compra_count
 * @property-read UnidadMedida|null $unidadMedida
 * @method static Builder|Producto acceptRequest(?array $request = null)
 * @method static Builder|Producto filter(?array $request = null)
 * @method static Builder|Producto ignoreRequest(?array $request = null)
 * @method static Builder|Producto newModelQuery()
 * @method static Builder|Producto newQuery()
 * @method static Builder|Producto query()
 * @method static Builder|Producto setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Producto setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Producto setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Producto whereCategoriaId($value)
 * @method static Builder|Producto whereCreatedAt($value)
 * @method static Builder|Producto whereId($value)
 * @method static Builder|Producto whereNombre($value)
 * @method static Builder|Producto whereTipo($value)
 * @method static Builder|Producto whereUnidadMedidaId($value)
 * @method static Builder|Producto whereUpdatedAt($value)
 * @mixin Eloquent
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

    private static array $whiteListFilter = [
        '*',
    ];

    public function toSearchableArray()
    {
//        $this->loadMissing('categoria'); // Carga la relación si no está cargada

        return [
            'nombre' => $this->nombre,
            'categoria' => $this->categoria ? $this->categoria->nombre : null, // Evita error si no hay categoría
        ];
    }

    public static function cantidadDetalles($id)
    {
        $detalles = DetalleProducto::where('producto_id', $id)->get();
        return count($detalles);
    }

    /**
     * La función "obtenerProductoPorNombre" recupera un producto por su nombre de la base de datos.
     *
     * @param string $nombre El parámetro "nombre" es una cadena que representa el nombre del producto a buscar.
     *
     * @return Builder|Model|object|Producto única instancia del modelo "Producto" que coincide con el nombre dado.
     */
    public static function obtenerProductoPorNombre(string $nombre)
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
