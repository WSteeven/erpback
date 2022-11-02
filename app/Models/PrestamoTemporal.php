<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class PrestamoTemporal extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;

    public $table = 'prestamos_temporales';
    public $fillable = [
        'fecha_salida',
        'fecha_devolucion',
        'observacion',
        'solicitante_id',
        'per_entrega_id',
        'per_recibe_id',
        'estado',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    const PENDIENTE = 'PENDIENTE';
    const DEVUELTO = 'DEVUELTO';

    /**
     * **********************************************
     * RELACIONES
     * **********************************************
     */
    /**
     * Relación muchos a muchos 
     */
    public function detalles(){
        return $this->belongsToMany(Inventario::class, 'inventario_prestamo_temporal', 'prestamo_id', 'inventario_id')
            ->withPivot('cantidad')
            ->withTimestamps()
            ->using(InventarioPrestamoTemporal::class);
    }

    /**
     * Relación uno a muchos (inversa).
     * Uno o varios prestamos pertencen a un solicitante
     */
    public function solicitante(){
        return $this->belongsTo(Empleado::class, 'solicitante_id', 'id');
    }

    /**
     * Relación uno a muchos (inversa).
     * Uno o varios prestamos pertencen a un solicitante
     */
    public function entrega(){
        return $this->belongsTo(Empleado::class, 'per_entrega_id', 'id');
    }

    /**
     * Relación uno a muchos (inversa).
     * Uno o varios prestamos pertencen a un solicitante
     */
    public function recibe(){
        return $this->belongsTo(Empleado::class, 'per_recibe_id', 'id');
    }

    /**********************************
     * MÉTODOS
     **********************************
     */
    /**
     * Obtener el listado de la tabla intermedia para mostrar en el resource.
     */
    public static function listadoProductos($id){
        $items = PrestamoTemporal::find($id)->detalles()->get();
        $results =[];
        $id=0;
        $row=[];
        foreach($items as $item){
            $row['id']=$item->id;
            $row['producto']=$item->detalle->producto->nombre;
            $row['detalle_id']=$item->detalle->descripcion;
            $row['cliente_id']=$item->cliente->empresa->razon_social;
            $row['sucursal_id']=$item->sucursal->lugar;
            $row['cantidad']=$item->cantidad;
            $row['prestados']=$item->prestados;
            $row['condicion']=$item->condicion->nombre;
            $row['estado']=$item->estado;
            $row['cantidades']=$item->pivot->cantidad;
            $results[$id]=$row;
            $id++;
        }
        return $results;
    }
}
