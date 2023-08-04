<?php

namespace App\Models;

use App\Models\ComprasProveedores\PreordenCompra;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Support\Facades\Log;
use Laravel\Scout\Searchable;

class DetalleProducto extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, Searchable;
    use AuditableModel;

    protected $table = "detalles_productos";
    // estado
    // const ACTIVO = "ACTIVO";
    // const INACTIVO = "INACTIVO";

    //TIPO
    const HOMBRE = 'HOMBRE';
    const MUJER = 'MUJER';


    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'producto_id',
        'descripcion',
        'marca_id',
        'modelo_id',
        'serial',
        'precio_compra',
        'color',
        'talla',
        'tipo',
        'url_imagen',

    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static $whiteListFilter = [
        '*',
    ];
    public function toSearchableArray()
    {
        return [
            'descripcion' => $this->descripcion,
            'serial' => $this->serial,
            'color' => $this->color,
            'talla' => $this->talla,
            'tipo' => $this->tipo,
        ];
    }

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
     /**
     * Relacion uno a muchos.
     * Un detalle tiene un codigo a la vez.
     */
    public function codigo()
    {
        return $this->hasOne(CodigoCliente::class, 'detalle_id', 'id');
    }

    /**
     * Relacion uno a muchos.
     * Un detalle de producto puede estar en muchos inventarios.
     */
    public function inventarios()
    {
        return $this->hasMany(Inventario::class);
    }

    public function detalle_stock($detalle_id, $sucursal_id)
    {
        // SELECT SUM(cantidad) FROM inventarios where detalle_id=500 group by detalle_id
        return Inventario::where('sucursal_id', $sucursal_id)->where('detalle_id', $detalle_id)->groupBy('detalle_id')->first('cantidad');
    }



    /**
     * Relación uno a muchos.
     * Un detalle de producto esta en varios detalle_producto_transaccion.
     */
    public function detallesTransaccion()
    {
        return $this->hasMany(DetalleProductoTransaccion::class);
    }

    /**
     * Relacion uno a muchos (inversa).
     * Uno o mas detalles de productos pertenecen a un producto en general
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Relacion uno a uno (inversa).
     * Un detalle de producto es 0 o 1 computadora o telefono
     */
    public function computadora()
    {
        return $this->hasOne(ComputadoraTelefono::class, 'detalle_id');
    }

    /**
     * Relacion uno a uno (inversa).
     * Un detalle de producto es 0 o 1 fibra
     */
    public function fibra()
    {
        return $this->hasOne(Fibra::class, 'detalle_id');
    }

    /**
     * Relación muchos a muchos.
     * Uno o varios detalles de producto estan en una devolución.
     */
    public function detalleProductoDevolucion()
    {
        return $this->belongsToMany(Devolucion::class, 'detalle_devolucion_producto', 'devolucion_id', 'detalle_id')
            ->withPivot('cantidad')->withTimestamps();
    }

    /**
     * Relación muchos a muchos.
     * Uno o varios detalles de producto estan en una devolución.
     */
    public function detalleProductoPedido()
    {
        return $this->belongsToMany(Pedido::class, 'detalle_pedido_producto', 'pedido_id', 'detalle_id')
            ->withPivot('cantidad')->withTimestamps();
    }
    
    
    /**
     * Relación muchos a muchos.
     * Uno o varios detalles de producto estan en una preorden.
     */
    public function detalleProductoPreordenCompra()
    {
        return $this->belongsToMany(PreordenCompra::class, 'cmp_item_detalle_preorden_compra', 'preorden_id', 'detalle_id')
            ->withPivot('cantidad')->withTimestamps();
    }



    /**
     * Relación muchos a muchos.
     * Uno o varios detalles de producto estan en una transacción.
     */
    public function detalleProductoTransaccion()
    {
        return $this->belongsToMany(TransaccionBodega::class, 'detalle_producto_transaccion', 'transaccion_id', 'detalle_id')
            ->withPivot(['cantidad_inicial', 'cantidad_final'])
            ->withTimestamps();
    }

    /**
     * Relacion uno a muchos.
     * Un detalle de producto tiene un control de stock diferente para cada sucursal.
     * Obtener los control de stock para un detalle.
     */
    public function control_stocks()
    {
        return $this->hasMany(ControlStock::class);
    }

    /**
     * Relacion uno a uno (inversa).
     * Un detalle de producto tiene 1 y solo 1 marca
     */
    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }
    /**
     * Relacion uno a uno (inversa).
     * Un detalle de producto tiene 1 y solo 1 modelo
     */
    public function modelo()
    {
        return $this->belongsTo(Modelo::class);
    }

    /**
     * Relación uno a uno (inversa).
     * Un detalle es un activo fijo.
     */
    public function activo()
    {
        return $this->hasOne(ActivoFijo::class);
    }

    /**
     * Relacion uno a muchos.
     * Un detalle de producto tiene varias imagenes.
     * Generalmente solo dos, pero queda la posibilidad de que sean más en un futuro.
     */
    public function imagenes()
    {
        return $this->hasMany(ImagenProducto::class);
    }

    /**
     * _______________________________
     * FUNCIONES
     * _______________________________
     */
}
