<?php

namespace App\Models;

use App\Http\Requests\DetalleProductoRequest;
use App\Models\Bodega\PermisoArma;
use App\Models\ComprasProveedores\OrdenCompra;
use App\Models\ComprasProveedores\PreordenCompra;
use App\Traits\UppercaseValuesTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Models\Audit;
use Request;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Throwable;

/**
 * App\Models\DetalleProducto
 *
 * @property int $id
 * @property int $producto_id
 * @property string $descripcion
 * @property int|null $marca_id
 * @property int|null $modelo_id
 * @property string|null $serial
 * @property float $precio_compra
 * @property string|null $color
 * @property string|null $talla
 * @property string|null $tipo
 * @property string|null $url_imagen
 * @property bool $activo
 * @property bool $es_fibra
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $fecha_caducidad
 * @property string|null $fotografia
 * @property string|null $fotografia_detallada
 * @property string|null $lote
 * @property string|null $calibre
 * @property string|null $peso
 * @property string|null $dimensiones
 * @property string|null $permiso
 * @property string|null $caducidad
 * @property int|null $permiso_id
 * @property bool $esActivo
 * @property-read ActivoFijo|null $activo_fijo
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Collection<int, Cliente> $clientes
 * @property-read int|null $clientes_count
 * @property-read CodigoCliente|null $codigo
 * @property-read ComputadoraTelefono|null $computadora
 * @property-read Collection<int, ControlStock> $control_stocks
 * @property-read int|null $control_stocks_count
 * @property-read Collection<int, Devolucion> $detalleProductoDevolucion
 * @property-read int|null $detalle_producto_devolucion_count
 * @property-read Collection<int, OrdenCompra> $detalleProductoOrdenCompra
 * @property-read int|null $detalle_producto_orden_compra_count
 * @property-read Collection<int, Pedido> $detalleProductoPedido
 * @property-read int|null $detalle_producto_pedido_count
 * @property-read Collection<int, PreordenCompra> $detalleProductoPreordenCompra
 * @property-read int|null $detalle_producto_preorden_compra_count
 * @property-read Collection<int, TransaccionBodega> $detalleProductoTransaccion
 * @property-read int|null $detalle_producto_transaccion_count
 * @property-read Collection<int, DetalleProductoTransaccion> $detallesTransaccion
 * @property-read int|null $detalles_transaccion_count
 * @property-read Fibra|null $fibra
 * @property-read Collection<int, ImagenProducto> $imagenes
 * @property-read int|null $imagenes_count
 * @property-read Collection<int, Inventario> $inventarios
 * @property-read int|null $inventarios_count
 * @property-read Collection<int, ItemDetallePreingresoMaterial> $itemsPreingresos
 * @property-read int|null $items_preingresos_count
 * @property-read Marca|null $marca
 * @property-read Modelo|null $modelo
 * @property-read PermisoArma|null $permisoArma
 * @property-read Producto $producto
 * @method static Builder|DetalleProducto acceptRequest(?array $request = null)
 * @method static Builder|DetalleProducto filter(?array $request = null)
 * @method static Builder|DetalleProducto ignoreRequest(?array $request = null)
 * @method static Builder|DetalleProducto newModelQuery()
 * @method static Builder|DetalleProducto newQuery()
 * @method static Builder|DetalleProducto query()
 * @method static Builder|DetalleProducto setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|DetalleProducto setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|DetalleProducto setLoadInjectedDetection($load_default_detection)
 * @method static Builder|DetalleProducto whereActivo($value)
 * @method static Builder|DetalleProducto whereCaducidad($value)
 * @method static Builder|DetalleProducto whereCalibre($value)
 * @method static Builder|DetalleProducto whereColor($value)
 * @method static Builder|DetalleProducto whereCreatedAt($value)
 * @method static Builder|DetalleProducto whereDescripcion($value)
 * @method static Builder|DetalleProducto whereDimensiones($value)
 * @method static Builder|DetalleProducto whereEsActivo($value)
 * @method static Builder|DetalleProducto whereEsFibra($value)
 * @method static Builder|DetalleProducto whereFechaCaducidad($value)
 * @method static Builder|DetalleProducto whereFotografia($value)
 * @method static Builder|DetalleProducto whereFotografiaDetallada($value)
 * @method static Builder|DetalleProducto whereId($value)
 * @method static Builder|DetalleProducto whereLote($value)
 * @method static Builder|DetalleProducto whereMarcaId($value)
 * @method static Builder|DetalleProducto whereModeloId($value)
 * @method static Builder|DetalleProducto wherePermiso($value)
 * @method static Builder|DetalleProducto wherePermisoId($value)
 * @method static Builder|DetalleProducto wherePeso($value)
 * @method static Builder|DetalleProducto wherePrecioCompra($value)
 * @method static Builder|DetalleProducto whereProductoId($value)
 * @method static Builder|DetalleProducto whereSerial($value)
 * @method static Builder|DetalleProducto whereTalla($value)
 * @method static Builder|DetalleProducto whereTipo($value)
 * @method static Builder|DetalleProducto whereIn($value)
 * @method static Builder|DetalleProducto whereUpdatedAt($value)
 * @method static Builder|DetalleProducto whereUrlImagen($value)
 * @property string|null $codigo_activo_fijo
 * @method static Builder|DetalleProducto whereCodigoActivoFijo($value)
 * @mixin Eloquent
 */
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
        'lote',
        'precio_compra',
        'vida_util',
        'color',
        'talla',
        'calibre',
        'peso',
        'dimensiones',
        'permiso',
        'permiso_id',
        'caducidad',
        'tipo',
        'url_imagen',
        'es_fibra',
        'esActivo',
        'fecha_caducidad',
        'fotografia',
        'fotografia_detallada',
        'codigo_activo_fijo',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'es_fibra' => 'boolean',
        'esActivo' => 'boolean',
        'activo' => 'boolean',
    ];

    private static $whiteListFilter = [
        '*',
    ];
    public function toSearchableArray()
    {
        return [
            'descripcion' => $this->descripcion,
            'producto' => $this->producto->nombre,
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
        return $this->hasMany(Inventario::class, 'detalle_id');
    }

    public function permisoArma()
    {
        return $this->hasOne(PermisoArma::class, 'id', 'permiso_id');
    }

    public function itemsPreingresos()
    {
        return $this->hasMany(ItemDetallePreingresoMaterial::class, 'detalle_id');
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
            ->withPivot(['cantidad_inicial', 'recibido'])
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
     * @param Request|DetalleProductoRequest $request El parámetro  es un objeto que contiene los datos de la solicitud HTTP,
     * como el método de solicitud, los encabezados y el cuerpo.
     * @param array|Collection $datos El parámetro "datos" es un arreglo que contiene los datos necesarios para crear un
     * nuevo registro "DetalleProducto". Las claves específicas y sus valores correspondientes en el
     * arreglo dependen de los requerimientos del modelo "DetalleProducto".
     *
     * @return DetalleProducto detalle recien creado .
     * @throws Exception|Throwable
     */
    public static function crearDetalle($request, array|Collection $datos) // Se quitó Request|DetalleProductoRequest porque no dejaba guardar desde la llamada en PreingresoMaterialService linea 131
    {
//        Log::channel('testing')->info('Log', ['Lo que se recibe para crear:', $request, $datos]);
        try {
            DB::beginTransaction();

            if (isset($datos['fotografia'])) $datos['fotografia'] = (new GuardarImagenIndividual($datos['fotografia'], RutasStorage::FOTOGRAFIAS_DETALLE_PRODUCTO))->execute();
            if (isset($datos['fotografia_detallada'])) $datos['fotografia_detallada'] = (new GuardarImagenIndividual($datos['fotografia_detallada'], RutasStorage::FOTOGRAFIAS_DETALLE_PRODUCTO))->execute();

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
            throw $e;
        }
        return $detalle;
    }

    /**
     * La función `obtenerDetalle` en PHP recupera detalles del producto en función de los parámetros
     * proporcionados, como el producto_id, la descripción y el número de serie.
     *
     * @param ?int $producto_id El ID del producto que desea buscar. Si se proporciona, la búsqueda se
     * limitará a este producto específico.
     * @param string $descripcion El parámetro "descripcion" es una cadena que representa la descripción del
     * detalle de producto. Se utiliza para buscar un producto con una descripción coincidente o similar.
     * @param string|null $serial El parámetro "serie" se utiliza para buscar un producto específico por su número
     * de serie. Si se proporciona un número de serie, la función buscará un producto con un número de
     * serie y una descripción coincidentes.
     *
     * @return DetalleProducto el resultado de la consulta a la base de datos.
     */
    public static function obtenerDetalle(string $descripcion, string|null $serial = null, int|null $producto_id = null)
    {
        if (!is_null($producto_id)) { // si hay producto_id realiza busqueda teniendo en cuenta la varible producto_id
            if (is_null($serial)) { // si no hay serial
                $result = DetalleProducto::where('producto_id', $producto_id)->where(function ($query) use ($descripcion) {
                    $query->where('descripcion', $descripcion)->orWhere('descripcion', 'LIKE', '%' . $descripcion   . '%'); // busca coincidencia exacta o similitud en el texto
                })->first();
            } else
                $result = DetalleProducto::where('producto_id', $producto_id)->where('serial', $serial)->where('descripcion', 'LIKE', '%' . $descripcion   . '%')->first();
        } else { // solo se buscará según la descripcion y/o numero serial
            if (is_null($serial)) $result  = DetalleProducto::where('descripcion', $descripcion)->orWhere('descripcion', 'LIKE', '%' . $descripcion   . '%')->first();
            else  $result  = DetalleProducto::where('serial', $serial)->where('descripcion', $descripcion)->orWhere('descripcion', 'LIKE', '%' . $descripcion   . '%')->first();
        }
        return $result;
    }
}
