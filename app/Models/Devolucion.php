<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class Devolucion extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;

    public $table = 'devoluciones';
    public $fillable = [
        'justificacion',
        'solicitante_id',
        'tarea_id',
        'observacion_aut',
        'autorizacion_id',
        'per_autoriza_id',
        'canton_id',
        'sucursal_id',
        'stock_personal',
        'causa_anulacion',
        'estado',
        'estado_bodega',
        'pedido_automatico',
        'cliente_id',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'stock_personal'=>'boolean',
        'pedido_automatico'=>'boolean',
    ];

    const CREADA = 'CREADA';
    const ANULADA = 'ANULADA';

    private static $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

    /**
     * Relación muchos a muchos(inversa).
     * Una devolución tiene varios detalles
     */
    public function detalles()
    {
        return $this->belongsToMany(DetalleProducto::class, 'detalle_devolucion_producto', 'devolucion_id', 'detalle_id')
            ->withPivot('cantidad', 'devuelto')->withTimestamps();
    }


    /**
     * Relación uno a muchos(inversa).
     * Una devolución pertenece a una o ninguna tarea
     */
    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }

    /**
     * Relacion uno a uno(inversa)
     * Uno o varios devoluciones se realizan en una sucursal
     */
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    /**
     * Relacion uno a uno(inversa)
     * Una o varias devoluciones pertenecen a una sucursal
     */
    public function canton()
    {
        return $this->belongsTo(Canton::class);
    }

    /**
     * Relacion uno a muchos (inversa).
     * Una o varias devoluciones pertenece a un solicitante
     */
    public function solicitante()
    {
        return $this->belongsTo(Empleado::class, 'solicitante_id', 'id');
    }
    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios pedidos son autorizados por una persona
     */
    public function autoriza()
    {
        return $this->belongsTo(Empleado::class, 'per_autoriza_id', 'id');
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
     * Relación polimorfica a una notificación.
     * Un pedido puede tener una o varias notificaciones.
     */
    public function notificaciones(){
        return $this->morphMany(Notificacion::class, 'notificable');
    }
    /**
     * Obtiene la ultima notificacion asociada a la devolucion.
     */
    public function latestNotificacion(){
        return $this->morphOne(Notificacion::class, 'notificable')->latestOfMany();
    }

    /**
     * Relacion polimorfica con Archivos uno a muchos.
     *
     */
    public function archivos(){
        return $this->morphMany(Archivo::class, 'archivable');
    }


    /**
     * ______________________________________________________________________________________
     * FUNCIONES
     * ______________________________________________________________________________________
     */
    /**
     * Obtener el listados de productos de una devolucion
     */
    public static function listadoProductos(int $id)
    {
        $detalles = Devolucion::find($id)->detalles()->get();
        $results = [];
        $id = 0;
        $row = [];
        foreach ($detalles as $detalle) {
            $condicion= $detalle->pivot->condicion_id? Condicion::find($detalle->pivot->condicion_id):null;
            $row['id'] = $detalle->id;
            $row['producto'] = $detalle->producto->nombre;
            $row['descripcion'] = $detalle->descripcion;
            $row['serial'] = $detalle->serial;
            $row['categoria'] = $detalle->producto->categoria->nombre;
            $row['cantidad'] = $detalle->pivot->cantidad;
            $row['condiciones'] = $condicion?->nombre;
            $row['observacion'] = $detalle->pivot->observacion;
            $row['devuelto'] = $detalle->pivot->devuelto;
            $results[$id] = $row;
            $id++;
        }

        return $results;
    }

    public static function filtrarDevolucionesEmpleadoConPaginacion($estado, $offset)
    {
        $results = [];
        switch ($estado) {
            case Devolucion::CREADA:
                $results = Devolucion::where('solicitante_id', auth()->user()->empleado->id)
                    ->where('estado', Devolucion::CREADA)
                    ->simplePaginate($offset);
                return $results;
            case Devolucion::ANULADA:
                $results = Devolucion::where('solicitante_id', auth()->user()->empleado->id)
                    ->where('estado', Devolucion::ANULADA)
                    ->simplePaginate($offset);
                return $results;
            default:
                $results = Devolucion::where('solicitante_id', auth()->user()->empleado->id)
                    ->simplePaginate($offset);
                return $results;
        }
    }
    public static function filtrarDevolucionesBodegueroConPaginacion($estado, $offset)
    {
        $results = [];
        switch ($estado) {
            case Devolucion::CREADA:
                $results = Devolucion::where('estado', Devolucion::CREADA)->simplePaginate($offset);
                return $results;

            case Devolucion::ANULADA:
                $results = Devolucion::where('estado','=', Devolucion::ANULADA)->simplePaginate($offset);
                return $results;
            default:
                $results = Devolucion::simplePaginate($offset);
                return $results;
        }
    }
}
