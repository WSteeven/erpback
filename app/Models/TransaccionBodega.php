<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class TransaccionBodega extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    use Filterable;

    public $table = 'transacciones_bodega';
    public $fillable = [
        'justificacion',
        'fecha_limite',
        'solicitante_id',
        'subtipo_id',
        'sucursal_id',
        'per_autoriza_id',
        'per_atiende_id',
        'lugar_destino',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];
    private static $whiteListFilter = ['*'];

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
        return $this->belongsToMany(DetalleProducto::class, 'detalle_productos_transacciones', 'transaccion_id', 'detalle_id')
            ->withPivot(['cantidad_inicial', 'cantidad_final'])
            ->withTimestamps();
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

    /* Una o varias transacciones tienen un solo tipo de transaccion*/
    public function subtipo()
    {
        return $this->belongsTo(SubtipoTransaccion::class);
    }
    /**
     * Obtener los movimientos para la transaccion
     */
    public function movimientos()
    {
        return $this->hasMany(MovimientoProducto::class);
    }

    /**
     * Relacion uno a uno(inversa)
     * Una o varias transacciones pertenecen a una sucursal
     */
    public function sucursal(){
        return $this->belongsTo(Sucursal::class);
    }


    
    /* Funciones */
    /**
     * Obtener la ultima autorizacion de una transaccion 
     */
    public static function ultimaAutorizacion($id)
    {
        $autorizaciones = TransaccionBodega::find($id)->autorizaciones()->get();
        $autorizacion = $autorizaciones->first();
        return $autorizacion;
    }
    public static function ultimoEstado($id)
    {
        $observaciones = TransaccionBodega::find($id)->estados()->get();
        $observacion = $observaciones->first();
        return $observacion;
    }

    public static function listadoProductos($id){
        $detalles = TransaccionBodega::find($id)->detalles()->get();
        $results = [];
        $id=0;
        $row=[];
        foreach($detalles as $detalle){
            $row['id']=$detalle->id;
            $row['producto']=$detalle->producto->nombre;
            $row['descripcion']=$detalle->descripcion;
            $row['categoria']=$detalle->producto->categoria->nombre;
            $row['cantidades']=$detalle->pivot->cantidad_inicial;
            $row['despachado']=$detalle->pivot->cantidad_final;
            $results[$id]=$row;
            $id++;
        }

        return $results;
    }
}
