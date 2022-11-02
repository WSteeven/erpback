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
        'comprobante',
        'fecha_limite',
        'solicitante_id',
        'subtipo_id',
        'tarea_id',
        'subtarea_id',
        'sucursal_id',
        'per_autoriza_id',
        'per_atiende_id',
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
        return $this->belongsToMany(DetalleProducto::class, 'detalle_producto_transaccion', 'transaccion_id', 'detalle_id')
            ->withPivot(['cantidad_inicial', 'cantidad_final'])
            ->withTimestamps();
    }

    /**
     * Relación uno a muchos(inversa).
     * Una transacción de ingreso pertenece a una o ninguna tarea
     */
    public function tarea(){
        return $this->belongsTo(Tarea::class);
    }

    /**
     * Relación uno a muchos(inversa).
     * Una transacción pertenece a una sola subtarea
     */
    public function subtarea(){
        return $this->belongsTo(Subtarea::class);
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

    /**
     * Filtra las transacciones para enviarlas de acuerdo al estado seleccionado, valor que se recibe desde los tabsOptions del front.
     * @param Collection $transacciones
     * @param String $estado
     * 
     * @return Collection $transacciones listado de transacciones filtradas
     */
    public static function filtrarTransacciones($transacciones, $estado){
        switch($estado){
            case 'ESPERA':
                return $transacciones->filter(fn ($transaccion) => self::ultimaAutorizacion($transaccion->id)->nombre==='PENDIENTE');// TransaccionBodega::ultimaAutorizacion($transaccion->id)->nombre === 'PENDIENTE');
            case 'PARCIAL':
                return $transacciones->filter(fn ($transaccion) => self::ultimoEstado($transaccion->id)->nombre === request('estado'));
            case 'PENDIENTE':
                return $transacciones->filter(fn ($transaccion) => self::ultimoEstado($transaccion->id)->nombre === request('estado') && self::ultimaAutorizacion($transaccion->id)->nombre === 'APROBADO');
            case 'COMPLETA':
                return $transacciones->filter(fn ($transaccion) => self::ultimoEstado($transaccion->id)->nombre === $estado);
            default:
                return $transacciones;
        }
    }
}
