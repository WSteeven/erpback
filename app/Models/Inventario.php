<?php

namespace App\Models;

use App\Models\ComprasProveedores\PreordenCompra;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

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
    private static $whiteListFilter = ['*'];

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
     * Uno o varios items del inventario estan en un traspaso.
     */
    public function detalleInventarioTraspaso()
    {
        return $this->belongsToMany(Traspaso::class, 'detalle_inventario_traspaso', 'traspaso_id', 'inventario_id')
            ->withPivot(['cantidad'])->withTimestamps();
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
     * Obtener los movimientos para el id de inventario
     */
    public function movimientos()
    {
        return $this->hasMany(MovimientoProducto::class);
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
     * Relación uno a muchos.
     * Un producto del inventario puede estar en muchas ubicaciones.
     */
    public function productoPercha()
    {
        return $this->hasMany(ProductoEnPercha::class);
    }

    /**
     * Relación muchos a muchos.
     * Uno o varios items del inventario estan en un prestamo temporal
     */
    // public function detallesPrestamoInventario()
    // {
    //     return $this->belongsToMany(PrestamoTemporal::class, 'inventario_prestamo_temporal', 'prestamo_id', 'inventario_id')
    //         ->withPivot('cantidad')
    //         ->withTimestamps()
    //         ->using(InventarioPrestamoTemporal::class);
    // }

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
        $datos = [
            'detalle_id' => $detalle_id,
            'sucursal_id' => $sucursal_id,
            'cliente_id' => $cliente_id,
            'condicion_id' => $condicion_id,
            'cantidad' => $cantidad
        ];
        return $datos;
    }

    /**
     * La función "contarExistenciasDetalleSerial" cuenta la cantidad total de artículos del inventario
     * para un determinado "detalle_id".
     *
     * @param detalle_id El parámetro "detalle_id" es el ID del detalle del que se quieren contar las
     * existencias.
     *
     * @return la suma del campo 'cantidad' de la colección 'existencias'.
     */
    public static function contarExistenciasDetalleSerial($detalle_id){
        $existencias = Inventario::where('detalle_id', $detalle_id)->get();
        return $existencias->sum('cantidad');
    }



    /**
     * It takes an array of data and returns the same array of data
     *
     * @param inventario_id The id of the inventory
     * @param detalle_producto_transaccion_id is the id of the detail of the transaction
     * @param cantidad quantity
     * @param precio_unitario the price of the product
     * @param saldo the current stock of the product
     * @param tipo 1 = entrada, 2 = salida
     *
     * @return an array with the following structure:
     * <code> = [
     *             'inventario_id' =&gt; ,
     *             'detalle_producto_transaccion_id' =&gt; ,
     *             'cantidad' =
     */
    public static function estructurarMovimiento($inventario_id, $detalle_producto_transaccion_id, $cantidad, $precio_unitario, $saldo, $tipo)
    {
        $datos = [
            'inventario_id' => $inventario_id,
            'detalle_producto_transaccion_id' => $detalle_producto_transaccion_id,
            'cantidad' => $cantidad,
            'precio_unitario' => $precio_unitario,
            'saldo' => $saldo,
            'tipo' => $tipo
        ];
        return $datos;
    }


    /**
     * Función para hacer ingreso masivo de elementos al inventario
     * @param int $sucursal_id as $sucursal
     * @param int $cliente_id as $cliente
     * @param int $condicion_id as $condicion
     * @param DetalleProducto[] $elementos
     */
    public static function ingresoMasivo(TransaccionBodega $transaccion, int $condicion, array $elementos)
    {
        try {
            DB::beginTransaction();
            Log::channel('testing')->info('Log', ['Elementos recibidos en el metodo de ingreso masivo', $elementos]);
            $elementos = $transaccion->items();
            foreach ($elementos as $elemento) {
                Log::channel('testing')->info('Log', ['Elemento dentro del foreach', $elemento]);
                $detalleTransaccion = DetalleProductoTransaccion::where('inventario_id', $elemento['id'])->where('transaccion_id', $transaccion->id)->first();
                $item = Inventario::where('detalle_id', $elemento['id'])
                    ->where('sucursal_id', $transaccion->sucursal_id)
                    ->where('cliente_id', $transaccion->cliente_id)
                    ->where('condicion_id', $condicion)
                    ->first();
                if ($item) {
                    Log::channel('testing')->info('Log', ['item encontrado en el inventario', $item]);
                    $cantidad = $elemento['cantidad'] + $item->cantidad;
                    $item->cantidad = $cantidad;
                    $item->save();

                    //Aqui va el registro de movimientos
                    $datos = self::estructurarMovimiento($item->id, $detalleTransaccion->id, $elemento['cantidad'], $elemento['precio_compra'], $item->cantidad, $transaccion->motivo->tipoTransaccion->nombre);
                    $movimiento = MovimientoProducto::create($datos);
                } else {
                    $datos = self::estructurarItem($elemento['id'], $transaccion->sucursal_id, $transaccion->cliente_id, $condicion, $elemento['cantidad']);
                    Log::channel('testing')->info('Log', ['item no encontrado en el inventario, se creará uno nuevo con los siguientes datos', $datos]);
                    $item = Inventario::create($datos);
                    Log::channel('testing')->info('Log', ['item creado es ', $item]);

                    //Aqui va el registro de movimientos
                    $datos = self::estructurarMovimiento($item->id, $detalleTransaccion->id, $elemento['cantidad'], $elemento['precio_compra'], $item->cantidad, $transaccion->motivo->tipoTransaccion->nombre);
                    $movimiento = MovimientoProducto::create($datos);
                }
                //Se crea la lista de movimientos
                Log::channel('testing')->info('Log', ['Se creó el movimiento', $movimiento]);
            }

            DB::commit();
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['[Inventario] Ha ocurrido un error', $e->getMessage(), $e->getLine()]);
            DB::rollBack();
            // throwException($e);
        }
    }


    public static function devolverProductos(int $sucursal, int $cliente_devuelve, array $elementos)
    {
        Log::channel('testing')->info('Log', ['Recibido en el metodo devolverProductos', $sucursal, $cliente_devuelve, $elementos]);
        try {
            DB::beginTransaction();
            foreach ($elementos as $elemento) {
                $itemRecibe = Inventario::find($elemento['id']);
                $detalle = DetalleProducto::find($itemRecibe->detalle_id);

                $itemDevuelve = Inventario::where('detalle_id', $detalle->id)
                    ->where('cliente_id', $cliente_devuelve)
                    ->where('sucursal_id', $sucursal)
                    ->where('condicion_id', $itemRecibe->condicion_id)->first();

                Log::channel('testing')->info('Log', ['Item que devuelve: ', $itemDevuelve]);
                $itemDevuelve->por_entregar -= $elemento['devolucion'];
                $itemDevuelve->cantidad -= $elemento['devolucion'];
                $itemDevuelve->save();

                $itemRecibe->por_recibir -= $elemento['devolucion'];
                $itemRecibe->cantidad += $elemento['devolucion'];
                $itemRecibe->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['Ha ocurrido un error devolviendo productos', $e->getMessage(), $e->getLine()]);
            throw $e;
        }
    }

    /* public static function devolverProductosParcial(int $sucursal, int $cliente_devuelve, array $elementos)
    {
        try {
            DB::beginTransaction();
            foreach ($elementos as $elemento) {
                $itemRecibe = Inventario::find($elemento['id']);
                $detalle = DetalleProducto::find($itemRecibe->detalle_id);

                $itemDevuelve = Inventario::where('detalle_id', $detalle->id)
                    ->where('cliente_id', $cliente_devuelve)
                    ->where('sucursal_id', $sucursal)
                    ->where('condicion_id', $itemRecibe->condicion_id)->first();

                $itemDevuelve->por_entregar -= $elemento['devolucion'];
                $itemDevuelve->cantidad -= $elemento['devolucion'];
                $itemDevuelve->save();

                $itemRecibe->por_recibir -= $elemento['devolucion'];
                $itemRecibe->cantidad += $elemento['devolucion'];
                $itemRecibe->save();
            }

            DB::commit();
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['Ha ocurrido un error devolviendo productos parciales', $e->getMessage(), $e->getLine()]);
            DB::rollBack();
            throw $e;
        }
    } */


    public static function traspasarProductos(int $sucursal, int $desde_cliente, Traspaso $traspaso, $hasta_cliente, array $elementos)
    {
        try {
            DB::beginTransaction();

            //primero restar productos de un cliente
            foreach ($elementos as $elemento) {
                $condicion = Condicion::where('nombre', $elemento['condiciones'])->first();
                $item = Inventario::find($elemento['id']);
                $detalle = DetalleProducto::find($item->detalle_id);
                Log::channel('testing')->info('Log', ['El detalle es', $detalle]);
                $item->cantidad -= $elemento['cantidades'];
                if ($item->por_entregar) {
                    $item->por_entregar -= $elemento['cantidades'];
                } else {
                    $item->por_recibir += $elemento['cantidades'];
                }
                $item->save();

                // $traspaso->items()->movimientos();


                //luego insertar productos en otro cliente
                $item = Inventario::where('detalle_id', $detalle->id)
                    ->where('sucursal_id', $sucursal)
                    ->where('cliente_id', $hasta_cliente)
                    ->where('condicion_id', $condicion->id)
                    ->first();
                Log::channel('testing')->info('Log', ['El item encontrado es', $item]);
                if ($item) {
                    $item->por_entregar += $elemento['cantidades'];
                    $item->cantidad += $elemento['cantidades'];
                    /* if($item->por_recibir){
                        $item->por_recibir += $elemento['cantidades'];
                    }else{
                        $item->por_entregar -= $elemento['cantidades'];
                    } */
                    $item->save();
                } else {
                    $datos = self::estructurarItem($detalle->id, $sucursal, $hasta_cliente, $condicion->id, $elemento['cantidades']);
                    Log::channel('testing')->info('Log', ['item no encontrado en el inventario, se creará uno nuevo con los siguientes datos', $datos]);
                    $itemCreado = Inventario::create($datos);
                    Log::channel('testing')->info('Log', ['El item creado es', $itemCreado]);
                    $itemCreado->por_entregar += $elemento['cantidades'];
                    $itemCreado->save();
                }
            }

            //luego insertar productos en otro cliente
            /* foreach ($elementos as $elemento) {
                $item = Inventario::where('id', $elemento['id'])->where('cliente_id', $hasta_cliente)->first();
                $item->cantidad+=$elemento['cantidad'];
                $item->save();
            } */


            DB::commit();
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['Ha ocurrido un error traspasando productos', $e->getMessage(), $e->getLine()]);
            DB::rollBack();
            throw $e;
        }
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
                    if(!in_array($detalle->producto->categoria->nombre, $categorias)){
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
     * @param sucursal_id El parámetro "sucursal_id" es el ID de la sucursal o bodega.
     *
     * @return un valor booleano. Si la condición es verdadera, devolverá verdadero. De lo contrario,
     * devolverá falso.
     */
    public static function verificarClienteSucursalPedido($sucursal_id)
    {
        $sucursal = Sucursal::find($sucursal_id); //Busca la bodega para saber si los detalles deben crear una orden de compra o no
        return $sucursal->cliente_id === Cliente::JEANPATRICIO || $sucursal->cliente_id === Cliente::JPCONSTRUCRED;
    }
}
