<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Termwind\Components\Raw;

class TransaccionBodega extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    use Filterable;

    public $table = 'transacciones_bodega';
    public $fillable = [
        'justificacion',
        'comprobante',
        'fecha_limite',
        'solicitante_id',
        'motivo_id',
        'tarea_id',
        'tipo_id',
        'sucursal_id',
        'cliente_id',
        'per_autoriza_id',
        'per_atiende_id',
        'per_retira_id',
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
    /* Una transaccion tiene varios estados de autorizacion durante su ciclo de vida */
    public function autorizaciones()
    {
        return $this->belongsToMany(Autorizacion::class, 'tiempo_autorizacion_transaccion', 'transaccion_id', 'autorizacion_id')
            ->withPivot('observacion')
            ->withTimestamps()
            ->orderByPivot('created_at', 'desc');
    }

    /* Una transaccion tiene varios estados durante su ciclo de vida */
    public function estados()
    {
        return $this->belongsToMany(EstadoTransaccion::class, 'tiempo_estado_transaccion', 'transaccion_id', 'estado_id')
            ->withPivot('observacion')
            ->withTimestamps()
            ->orderByPivot('created_at', 'desc');
    }

    //Una transaccion tiene varios productos solicitados
    public function detalles()
    {
        return $this->belongsToMany(DetalleProducto::class, 'detalle_producto_transaccion', 'transaccion_id', 'detalle_id')
            ->withPivot(['cantidad_inicial', 'cantidad_final'])
            ->withTimestamps();
    }
    /**
     * Relación uno a muchos.
     * Una transaccion tiene varios detalle_producto_transaccion.
     */
    public function detallesTransaccion()
    {
        return $this->hasMany(DetalleProductoTransaccion::class, 'id');
    }
    /* Una o varias transacciones tienen un solo motivo*/
    public function motivo()
    {
        return $this->belongsTo(Motivo::class);
    }
    /**
     * Relación uno a muchos(inversa).
     * Una transacción de EGRESO pertenece a una o ninguna tarea
     */
    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }

    /**
     * Relación uno a muchos(inversa).
     * Una transacción pertenece a un solo tipo
     */
    public function tipo()
    {
        return $this->belongsTo(TipoTransaccion::class);
    }
    /**
     * Relacion uno a uno(inversa)
     * Una o varias transacciones pertenecen a una sucursal
     */
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    /**
     * Relacion uno a uno(inversa)
     * Una o varias transacciones pertenecen a un cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relacion uno a muchos (inversa).
     * Una o varias transacciones pertenece a un solicitante 
     */
    public function solicitante()
    {
        return $this->belongsTo(Empleado::class, 'solicitante_id', 'id');
    }
    /**
     * Relacion uno a muchos (inversa).
     * Una y solo una persona puede autorizar la transaccion
     */
    public function autoriza()
    {
        return $this->belongsTo(Empleado::class, 'per_autoriza_id', 'id');
    }

    /**
     * Relacion uno a muchos (inversa).
     * Una y solo una persona puede autorizar la transaccion
     */
    public function atiende()
    {
        return $this->belongsTo(Empleado::class, 'per_atiende_id', 'id');
    }

    /**
     * Relacion uno a muchos (inversa).
     * Una y solo una persona puede autorizar la transaccion
     */
    public function retira()
    {
        return $this->belongsTo(Empleado::class, 'per_retira_id', 'id');
    }


    /**
     * ______________________________________________________________________________________
     * FUNCIONES
     * ______________________________________________________________________________________
     */
    /**
     * Obtener la ultima autorizacion de una transaccion 
     */
    public static function ultimaAutorizacion($id)
    {
        $autorizaciones = TransaccionBodega::find($id)->autorizaciones()->get();
        $autorizacion = $autorizaciones->first();
        return $autorizacion;
    }
    /**
     * Obtener el ultimo estado de una transaccion 
     */
    public static function ultimoEstado($id)
    {
        $observaciones = TransaccionBodega::find($id)->estados()->get();
        $observacion = $observaciones->first();
        return $observacion;
    }

    /**
     * Obtener el listado de productos de una transaccion
     */
    public static function listadoProductos($id)
    {
        $detalles = TransaccionBodega::find($id)->detalles()->get();
        $results = [];
        $id = 0;
        $row = [];
        foreach ($detalles as $detalle) {
            Log::channel('testing')->info('Log', ['Foreach de movimientos de devoluciones del  traspaso:', $detalle]);
            $detalleProductoTransaccion = DetalleProductoTransaccion::withSum('devoluciones', 'cantidad')->where('transaccion_id', $detalle->pivot->transaccion_id)->where('detalle_id', $detalle->id)->first();
            $row['id'] = $detalle->id;
            $row['detalle_id'] = $detalle->pivot->detalle_id;
            $row['producto'] = $detalle->producto->nombre;
            $row['descripcion'] = $detalle->descripcion;
            $row['categoria'] = $detalle->producto->categoria->nombre;
            $row['cantidad'] = $detalle->pivot->cantidad_inicial;
            $row['despachado'] = $detalle->pivot->cantidad_final;
            $row['devuelto']=$detalleProductoTransaccion->devoluciones_sum_cantidad;
            $results[$id] = $row;
            $id++;
        }

        return $results;
    }




    /**
     * Función para obtener todas las columnas de la tabla.
     */
    /* public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    } */
}
