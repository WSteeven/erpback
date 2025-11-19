<?php

namespace App\Models;

use App\Models\Bodega\Lote;
use App\Models\ComprasProveedores\PreordenCompra;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Inventario
 *
 * @method static whereIn(string $string, $ids_detalles)
 * @property int $id
 * @property int $detalle_id
 * @property int $sucursal_id
 * @property int $cliente_id
 * @property int $por_recibir
 * @property int $cantidad
 * @property int $por_entregar
 * @property int $condicion_id
 * @property string $estado
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Cliente|null $cliente
 * @property-read Condicion|null $condicion
 * @property-read DetalleProducto|null $detalle
 * @property-read Collection<int, Transferencia> $detalleInventarioTransferencia
 * @property-read int|null $detalle_inventario_transferencia_count
 * @property-read int|null $detalle_inventario_traspaso_count
 * @property-read Collection<int, TransaccionBodega> $detalleProductoTransaccion
 * @property-read int|null $detalle_producto_transaccion_count
 * @property-read int|null $movimientos_count
 * @property-read int|null $producto_percha_count
 * @property-read Sucursal|null $sucursal
 * @method static Builder|Inventario acceptRequest(?array $request = null)
 * @method static Builder|Inventario filter(?array $request = null)
 * @method static Builder|Inventario ignoreRequest(?array $request = null)
 * @method static Builder|Inventario newModelQuery()
 * @method static Builder|Inventario newQuery()
 * @method static Builder|Inventario query()
 * @method static Builder|Inventario setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Inventario setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Inventario setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Inventario whereCantidad($value)
 * @method static Builder|Inventario whereClienteId($value)
 * @method static Builder|Inventario whereCondicionId($value)
 * @method static Builder|Inventario whereCreatedAt($value)
 * @method static Builder|Inventario whereDetalleId($value)
 * @method static Builder|Inventario whereEstado($value)
 * @method static Builder|Inventario whereId($value)
 * @method static Builder|Inventario wherePorEntregar($value)
 * @method static Builder|Inventario wherePorRecibir($value)
 * @method static Builder|Inventario whereSucursalId($value)
 * @method static Builder|Inventario whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Inventario extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;


    protected $table = "inventarios";
    protected $fillable = [
        'detalle_id',
        'sucursal_id',
        'cliente_id',
        'condicion_id',
        'por_recibir',
        'cantidad',
        'por_entregar',
        'estado',
    ];

    const INVENTARIO = "INVENTARIO";
    const TRANSITO = "TRANSITO";
    const SIN_STOCK = "SIN STOCK";

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    /**
     * Eloquent Filtering
     */
    private static array $whiteListFilter = ['*'];

    // private $aliasListFilter = [
    //     'cliente.empresa.razon_social'=>'cliente',
    //     'sucursal.lugar'=>'sucursal',
    //     'condicion.nombre'=>'condicion',
    //     'detalle.descripcion'=>'descripcion',
    // ];

    // public function toSearchableArray()
    // {
    //     return $this->with('detalle')->toArray();
    //     // return [
    //     //     'detalles_productos.descripcion' => $this->detalle->descripcion,
    //     //     // 'sucursal_id'=> $this->with('sucursal')->where('id', '=',$this->sucursal_id)->first()->toArray(),
    //     // ];
    // }

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

    public function lotes()
    {
        return $this->hasMany(Lote::class, 'inventario_id');
    }

    public function lotesDisponible()
    {
        return $this->lotes()->where('cant_disponible', '>', 0)->orderBy('fecha_vencimiento', 'desc');
    }

    /**
     * Relación muchos a muchos.
     * Uno o varios detalles de producto estan en una transacción.
     */
    public function detalleProductoTransaccion()
    {
        return $this->belongsToMany(TransaccionBodega::class, 'detalle_producto_transaccion', 'transaccion_id', 'inventario_id')
            ->withPivot(['cantidad_inicial', 'recibido'])
            ->withTimestamps();
    }

    /**
     * Relación muchos a muchos.
     * Uno o varios items del inventarion estan en una transferencia
     */
    public function detalleInventarioTransferencia()
    {
        return $this->belongsToMany(Transferencia::class, 'detalle_inventario_transferencia', 'transferencia_id', 'inventario_id')
            ->withPivot(['cantidad'])->withTimestamps();
    }

    /**
     * Relacion uno a muchos (inversa)
     * Muchos inventarios tienen un mismo detalle
     */
    public function detalle()
    {
        return $this->belongsTo(DetalleProducto::class);
    }

    /**
     * Relacion uno a uno (inversa)
     * Muchos inventarios tienen una sucursal
     */
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    /**
     * Relacion uno a uno (inversa)
     * Muchos inventarios tienen una sucursal
     */
    public function condicion()
    {
        return $this->belongsTo(Condicion::class);
    }

    /**
     * Relacion uno a uno (inversa)
     * Un item del inventario pertenece a un cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }


    /**
     * ______________________________________________________________________________________
     * FUNCIONES
     * ______________________________________________________________________________________
     */
    /**
     * Función para crear la estructura del array de datos para el ingreso de un item del inventario.
     */
    public static function estructurarItem($detalle_id, $sucursal_id, $cliente_id, $condicion_id, $cantidad)
    {
        return [
            'detalle_id' => $detalle_id,
            'sucursal_id' => $sucursal_id,
            'cliente_id' => $cliente_id,
            'condicion_id' => $condicion_id,
            'cantidad' => $cantidad
        ];
    }

    /**
     * La función "contarExistenciasDetalleSerial" cuenta la cantidad total de artículos del inventario
     * para un determinado "detalle_id".
     *
     * @param int $detalle_id El parámetro "detalle_id" es el ID del detalle del que se quieren contar las
     * existencias.
     *
     * @return int la suma del campo 'cantidad' de la colección 'existencias'.
     */
    public static function contarExistenciasDetalleSerial(int $detalle_id)
    {
        $existencias = Inventario::where('detalle_id', $detalle_id)->get();
        return $existencias->sum('cantidad');
    }


    /**
     * La función "verificarExistenciasDetalles" verifica si hay suficientes artículos en inventario
     * para cada detalle en un pedido determinado y genera una preorden si es necesario.
     *
     * @param pedido El parámetro "pedido" es un objeto que representa un pedido en el sistema. Se
     * utiliza para comprobar la existencia de detalles en el pedido.
     */
    public static function verificarExistenciasDetalles($pedido)
    {

        //estas categorias no generan preorden de compra
        $categorias = [
            'EQUIPO PROPIO',
            'EQUIPO',
            'UNIFORME',
            'EPP',
            'INFORMATICA',
        ];


        if (self::verificarClienteSucursalPedido($pedido->sucursal_id)) {
            //obtener todas las sucursales pertenecientes a jpconstrucred o jeanpazmino
            $ids_sucursales = Sucursal::whereIn('cliente_id', [Cliente::JPCONSTRUCRED, Cliente::JEANPATRICIO])->get('id');
            $items = [];
            try {
                foreach ($pedido->detalles as $index => $detalle) {
                    Log::channel('testing')->info('Log', ['El detalle es', $detalle]);
                    // aqui se verifica si el detalle no pertenece a la categoria del array $categorias para continuar a generar la preorden
                    if (!in_array($detalle->producto->categoria->nombre, $categorias)) {
                        Log::channel('testing')->info('Log', ['La categoria es', $detalle->producto->categoria->nombre]);

                        $itemsInventario = Inventario::where('detalle_id', $detalle['id'])->whereIn('sucursal_id', $ids_sucursales)->whereIn('condicion_id', [Condicion::NUEVO, Condicion::USADO])->get();
                        if ($itemsInventario->sum('cantidad') < $detalle->pivot->cantidad) {
                            $row['detalle_id'] = $detalle->id;
                            $row['cantidad'] = $detalle->pivot->cantidad - $itemsInventario->sum('cantidad');
                            Log::channel('testing')->info('Log', ['Lo que se va a agregar a los items de la preorden', $row]);
                            $items[$index] = $row;
                        }
                    }
                }
                Log::channel('testing')->info('Log', ['Todos los items', $items]);
                if (count($items) > 0) //Si hay al menos un item cuya cantidad no esté en inventario se genera una preorden de compra
                    PreordenCompra::generarPreorden($pedido, $items);


            } catch (Exception $e) {
                Log::channel('testing')->info('Log', ['Ha ocurrido un error en el metodo verificarExistencias', $e->getMessage(), $e->getLine()]);
            }
        }
    }

    /**
     * La función "verificarClienteSucursalPedido" comprueba si una sucursal determinada pertenece a un
     * cliente específico.
     *
     * @param int $sucursal_id El parámetro "sucursal_id" es el ID de la sucursal o bodega.
     *
     * @return boolean un valor booleano. Si la condición es verdadera, devolverá verdadero. De lo contrario,
     * devolverá falso.
     */
    public static function verificarClienteSucursalPedido(int $sucursal_id)
    {
        $sucursal = Sucursal::find($sucursal_id); //Busca la bodega para saber si los detalles deben crear una orden de compra o no
        return $sucursal->cliente_id === Cliente::JEANPATRICIO || $sucursal->cliente_id === Cliente::JPCONSTRUCRED;
    }
}
