<?php

namespace App\Models;

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

    public $table = 'devoluciones';
    public $fillable = [
        'justificacion',
        'solicitante_id',
        'tarea_id',
        'sucursal_id',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
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
            ->withPivot('cantidad')->withTimestamps();
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
     * Una o varias devoluciones pertenecen a una sucursal
     */
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
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
            $row['id'] = $detalle->id;
            $row['producto'] = $detalle->producto->nombre;
            $row['descripcion'] = $detalle->descripcion;
            $row['categoria'] = $detalle->producto->categoria->nombre;
            $row['cantidad'] = $detalle->pivot->cantidad;
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
