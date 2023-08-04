<?php

namespace App\Models\ComprasProveedores;

use App\Models\Autorizacion;
use App\Models\DetalleProducto;
use App\Models\Empleado;
use App\Models\Pedido;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class PreordenCompra extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait;
    use Filterable;
    use AuditableModel;

    protected $table = 'cmp_preordenes_compras';
    public $fillable = [
        'solicitante_id',
        'pedido_id',
        'autorizador_id',
        'autorizacion_id',
    ];

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
     * Una preorden tiene varios detalles
     */
    public function detalles()
    {
        return $this->belongsToMany(DetalleProducto::class, 'cmp_item_detalle_preorden_compra', 'preorden_id', 'detalle_id')
            ->withPivot('cantidad')->withTimestamps();
    }

    /**
     * Relación uno a uno
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios pedidos pertenece a un solicitante
     */
    public function solicitante()
    {
        return $this->belongsTo(Empleado::class, 'solicitante_id', 'id');
    }

    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios pedidos son autorizados por una persona
     */
    public function autorizador()
    {
        return $this->belongsTo(Empleado::class, 'autorizador_id', 'id');
    }

    /**
     * Relación uno a uno(inversa).
     * Uno o varios pedidos solo pueden tener una autorización.
     */
    public function autorizacion()
    {
        return $this->belongsTo(Autorizacion::class);
    }

    /**
     * ______________________________________________________________________________________
     * FUNCIONES
     * ______________________________________________________________________________________
     */

    /**
     * La función "generarPreorden" crea una preorden anticipado para una compra en función de un pedido y
     * artículos determinados.
     * 
     * @param pedido El parámetro "pedido" es un objeto que representa un pedido en el sistema.
     * Contiene información como la identificación del solicitante (solicitante), la identificación del
     * autorizador (autorizador) y la identificación de la autorización (autorización).
     * @param items El parámetro "elementos" es una matriz de elementos que se asociarán con la
     * preorden. Cada elemento de la matriz representa un detalle del pedido previo y debe contener la
     * información necesaria para crear el registro de detalle en la base de datos.
     */
    public static function generarPreorden($pedido, $items)
    {
        try {
            DB::beginTransaction();
            $preorden = PreordenCompra::create([
                'solicitante_id' => $pedido->solicitante_id,
                'pedido_id' => $pedido->id,
                'autorizador_id' => $pedido->per_autoriza_id,
                'autorizacion_id' => $pedido->autorizacion_id,
            ]);

            // guardar los detalles en la preorden
            $preorden->detalles()->sync($items);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['Ha ocurrido un error al generar la preorden de compra', $e->getMessage(), $e->getLine()]);
        }
    }

    public static function listadoProductos(int $id)
    {
        $detalles = PreordenCompra::find($id)->detalles()->get();
        $results = [];
        $row = [];
        foreach ($detalles as $index => $detalle) {
            $row['id'] = $detalle->id;
            $row['producto'] = $detalle->producto->nombre;
            $row['descripcion'] = $detalle->descripcion;
            $row['categoria'] = $detalle->producto->categoria->nombre;
            $row['unidad_medida'] = $detalle->producto->unidadMedida->nombre;
            $row['serial'] = $detalle->serial;
            $row['cantidad'] = $detalle->pivot->cantidad;
            // $row['precio_unitario'] = 0;
            $row['iva'] = 0;
            $row['subtotal'] = 0;
            $row['total'] = 0;
            $results[$index] = $row;
        }

        return $results;
    }
}
