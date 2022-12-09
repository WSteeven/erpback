<?php

namespace App\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Traspaso extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;

    public $table = 'traspasos';
    public $fillable = [
        'justificacion',
        'devuelta',
        'solicitante_id',
        'desde_cliente_id',
        'hasta_cliente_id',
        'tarea_id',
        'estado_id',
        'sucursal_id',
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
     * Relación muchos a muchos(inversa).
     * Un traspaso tiene varios items del inventario
     */
    public function items()
    {
        return $this->belongsToMany(Inventario::class, 'detalle_inventario_traspaso', 'traspaso_id', 'inventario_id')
            ->withPivot('cantidad')->withTimestamps();
    }
    /**
     * Relación uno a muchos(inversa).
     * Un traspaso pertenece a una o ninguna tarea
     */
    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }
    /**
     * Relacion uno a uno(inversa)
     * Uno o varios traspasos pertenecen a una sucursal
     */
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }
    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios traspasos pertenece a un solicitante 
     */
    public function solicitante()
    {
        return $this->belongsTo(Empleado::class, 'solicitante_id', 'id');
    }
    
    /**
     * Relacion uno a uno(inversa)
     * Uno o varios traspasos se hacen desde un cliente
     */
    public function prestamista()
    {
        return $this->belongsTo(Cliente::class, 'desde_cliente_id', 'id');
    }
    
    /**
     * Relacion uno a uno(inversa)
     * Uno o varios traspasos se hacen hasta un cliente
     */
    public function prestatario()
    {
        return $this->belongsTo(Cliente::class, 'hasta_cliente_id', 'id');
    }
    /**
     * Relacion uno a uno(inversa)
     * Uno o varios traspasos tienen un estado
     */
    public function estado()
    {
        return $this->belongsTo(EstadoTransaccion::class);
    }

    /**
     * ______________________________________________________________________________________
     * FUNCIONES
     * ______________________________________________________________________________________
     */
    /**
     * Obtener el listados de productos de un traspaso
     */
    public static function listadoProductos(int $id)
    {
        $items = Traspaso::find($id)->items()->get();
        $results = [];
        $id = 0;
        $row = [];
        foreach ($items as $item) {
            $row['id'] = $item->id;
            $row['producto'] = $item->detalle->producto->nombre;
            $row['detalle_id'] = $item->detalle->descripcion;
            $row['cliente_id'] = $item->cliente->empresa->razon_social;
            $row['condicion'] = $item->condicion->nombre;
            // $row['categoria'] = $item->detalle->producto->categoria->nombre;
            $row['cantidades'] = $item->pivot->cantidad;
            $results[$id] = $row;
            $id++;
        }

        return $results;
    }
}
