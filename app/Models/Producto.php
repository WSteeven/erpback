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
