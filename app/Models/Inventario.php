<?php

namespace App\Models;

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

    private static $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
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
    public function detallesPrestamoInventario()
    {
        return $this->belongsToMany(PrestamoTemporal::class, 'inventario_prestamo_temporal', 'prestamo_id', 'inventario_id')
            ->withPivot('cantidad')
            ->withTimestamps()
            ->using(InventarioPrestamoTemporal::class);
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
     * Función para formar la estructura de datos de un registro de movimiento
     * @param $inventario_id Id del ítem del inventario
     * @param $detalle_producto_transaccion_id Id del DetalleProductoTransaccion
     * @param $cantidad Cantidad que se esta registrando
     * @param $precio_unitario Precio unitario del ítem
     * @param $saldo La cantidad de existencias ahora del item en inventario
     * @param $inventario_id Id del ítem del inventario
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
            foreach ($elementos as $elemento) {
                Log::channel('testing')->info('Log', ['Elemento dentro del foreach', $elemento]);
                $detalleTransaccion = DetalleProductoTransaccion::where('detalle_id', $elemento['id'])->where('transaccion_id', $transaccion->id)->first();
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
                    $datos = self::estructurarMovimiento($item->id, $detalleTransaccion->id, $elemento['cantidad'],$elemento['precio_compra'], $item->cantidad, $transaccion->motivo->tipoTransaccion->nombre);
                    $movimiento = MovimientoProducto::create($datos);
                } else {
                    $datos = self::estructurarItem($elemento['id'], $transaccion->sucursal_id, $transaccion->cliente_id, $condicion, $elemento['cantidad']);
                    Log::channel('testing')->info('Log', ['item no encontrado en el inventario, se creará uno nuevo con los siguientes datos', $datos]);
                    $item = Inventario::create($datos);
                    Log::channel('testing')->info('Log', ['item creado es ', $item]);

                    //Aqui va el registro de movimientos
                    $datos = self::estructurarMovimiento($item->id, $detalleTransaccion->id, $elemento['cantidad'],$elemento['precio_compra'], $item->cantidad, $transaccion->motivo->tipoTransaccion->nombre);
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
                $item = Inventario::where('detalle_id', $detalle->id)->where('cliente_id', $hasta_cliente)->first();
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
                    $datos = self::estructurarItem($detalle->id, $sucursal, $hasta_cliente, 1, $elemento['cantidades']);
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
}
