<?php

namespace App\Models;

use App\Models\ComprasProveedores\OrdenCompra;
use App\Models\ComprasProveedores\PreordenCompra;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Support\Facades\DB;
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

    /**
     * Relacion muchos a muchos.
     * Un detalle puede pertenecer a varios clientes en el inventario.
     */
    public function clientes()
    {
        return $this->belongsToMany(Cliente::class, 'cliente_id', 'detalle_id');
    }

    public function detalle_stock($detalle_id, $sucursal_id)
    {
        // SELECT SUM(cantidad) FROM inventarios where detalle_id=500 group by detalle_id
        return Inventario::where('sucursal_id', $sucursal_id)->where('detalle_id', $detalle_id)->orderBy('cantidad', 'desc')->first('cantidad');
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
            ->withPivot('cantidad', 'solicitante_id')->withTimestamps();
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
     * Uno o varios detalles de producto estan en una preorden.
     */
    public function detalleProductoOrdenCompra()
    {
        return $this->belongsToMany(OrdenCompra::class, 'cmp_item_detalle_orden_compra', 'orden_compra_id', 'detalle_id')
            ->withPivot(['cantidad', 'porcentaje_descuento', 'facturable', 'grava_iva', 'precio_unitario', 'iva', 'subtotal', 'total'])->withTimestamps();
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
    public function activo_fijo()
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

    /**
     * La función `crearDetalle` crea un nuevo registro `DetalleProducto` en la base de datos y lo
     * asocia con un registro `Computadora` o `Fibra` según el valor del campo `categoría` en la
     * solicitud.
     *
     * @param request El parámetro  es un objeto que contiene los datos de la solicitud HTTP,
     * como el método de solicitud, los encabezados y el cuerpo.
     * @param datos El parámetro "datos" es un arreglo que contiene los datos necesarios para crear un
     * nuevo registro "DetalleProducto". Las claves específicas y sus valores correspondientes en el
     * arreglo dependen de los requerimientos del modelo "DetalleProducto".
     *
     * @return la variable .
     */
    public static function crearDetalle($request, $datos)
    {
        Log::channel('testing')->info('Log', ['Lo que se recibe para crear:', $request, $datos]);
        try {
            DB::beginTransaction();

            $detalle = DetalleProducto::create($datos);
            if ($request->categoria === 'INFORMATICA') {

                $detalle->computadora()->create([
                    'memoria_id' => $datos['ram'],
                    'disco_id' => $datos['disco'],
                    'procesador_id' => $datos['procesador'],
                    'imei' => $datos['imei'],
                ]);
                DB::commit();
            }
            if ($request->es_fibra) {
                $detalle->fibra()->create([
                    'span_id' => $datos['span'],
                    'tipo_fibra_id' => $datos['tipo_fibra'],
                    'hilo_id' => $datos['hilos'],
                    'punta_inicial' => $datos['punta_inicial'],
                    'punta_final' => $datos['punta_final'],
                    'custodia' => $datos['custodia'],
                ]);
                DB::commit();
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
        return $detalle;
    }

    /**
     * La función `obtenerDetalle` en PHP recupera detalles del producto en función de los parámetros
     * proporcionados, como el producto_id, la descripción y el número de serie.
     * 
     * @param producto_id El ID del producto que desea buscar. Si se proporciona, la búsqueda se
     * limitará a este producto específico.
     * @param descripcion El parámetro "descripcion" es una cadena que representa la descripción del
     * detalle de producto. Se utiliza para buscar un producto con una descripción coincidente o similar.
     * @param serial El parámetro "serie" se utiliza para buscar un producto específico por su número
     * de serie. Si se proporciona un número de serie, la función buscará un producto con un número de
     * serie y una descripción coincidentes.
     * 
     * @return DetalleProducto el resultado de la consulta a la base de datos.
     */
    public static function obtenerDetalle($producto_id = null, $descripcion, $serial = null)
    {
        if (!is_null($producto_id)) { // si hay producto_id realiza busqueda teniendo en cuenta la varible producto_id
            if (is_null($serial)) { // si no hay serial
                $result = DetalleProducto::where('producto_id', $producto_id)->where(function ($query) use ($descripcion) {
                    $query->where('descripcion', $descripcion)->orWhere('descripcion', 'LIKE', '%' . $descripcion   . '%'); // busca coincidencia exacta o similitud en el texto
                })->first();
            } else
                $result = DetalleProducto::where('producto_id', $producto_id)->where('descripcion', $descripcion)->where('serial', $serial)->first();
        } else { // solo se buscará según la descripcion y/o numero serial
            if (is_null($serial)) $result  = DetalleProducto::where('descripcion', $descripcion)->orWhere('descripcion', 'LIKE', '%' . $descripcion   . '%')->first();
            else  $result  = DetalleProducto::where('serial', $serial)->where('descripcion', $descripcion)->orWhere('descripcion', 'LIKE', '%' . $descripcion   . '%')->first();
        }
        return $result;
    }
}
