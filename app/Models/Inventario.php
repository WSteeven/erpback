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
    public function detalleInvetarioTraspaso()
    {
        return $this->belongsToMany(Traspaso::class, 'detalle_inventario_traspaso', 'traspaso_id', 'inventario_id')
            ->withPivot('cantidad')->withTimestamps();
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
     * Función para hacer ingreso masivo de elementos al inventario
     * @param int $sucursal_id as $sucursal
     * @param int $cliente_id as $cliente
     * @param int $condicion_id as $condicion
     * @param DetalleProducto[] $elementos 
     */
    public static function ingresoMasivo(int $sucursal, int $cliente, int $condicion, array $elementos)
    {
        try {
            DB::beginTransaction();
            Log::channel('testing')->info('Log', ['Elementos recibidos en el metodo de ingreso masivo', $elementos]);
            foreach ($elementos as $elemento) {
                $item = Inventario::where('detalle_id', $elemento['id'])
                    ->where('sucursal_id', $sucursal)
                    ->where('cliente_id', $cliente)
                    ->where('condicion_id', $condicion)
                    ->first();
                if ($item) {
                    Log::channel('testing')->info('Log', ['item encontrado en el inventario', $item]);
                    $cantidad = $elemento['cantidad'] + $item->cantidad;
                    $item->cantidad = $cantidad;
                    $item->save();
                } else {
                    $datos = [
                        'detalle_id' => $elemento['id'],
                        'sucursal_id' => $sucursal,
                        'cliente_id' => $cliente,
                        'condicion_id' => $condicion,
                        'cantidad' => $elemento['cantidad'],
                    ];
                    Log::channel('testing')->info('Log', ['item no encontrado en el inventario, se creará uno nuevo con los siguientes datos', $datos]);
                    Inventario::create($datos);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['Ha ocurrido un error en el ingreso masivo', $e->getMessage(), $e->getLine()]);
            DB::rollBack();
            // throwException($e);
        }
    }
}
