<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Inventario extends Model
{
    use HasFactory;
    protected $table = "inventarios";
    protected $fillable = [
        'detalle_id',
        'sucursal_id',
        'cliente_id',
        'condicion_id',
        'cantidad',
        'prestados',
        'estado',
    ];

    const INVENTARIO = "INVENTARIO";
    const TRANSITO = "TRANSITO";
    const SIN_STOCK = "SIN STOCK";
    

    /*****************************
     * RELACIONES
     * ***************************
     */
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
    public function detalle(){
        return $this->belongsTo(DetalleProducto::class);
    }
    /**
     * Relacion uno a uno (inversa)
     * Muchos inventarios tienen una sucursal
     */
    public function sucursal(){
        return $this->belongsTo(Sucursal::class);
    }
    /**
     * Relacion uno a uno (inversa)
     * Muchos inventarios tienen una sucursal
     */
    public function condicion(){
        return $this->belongsTo(Condicion::class);
    }    
    /**
     * Relacion uno a uno (inversa)
     * Un item del inventario pertenece a un cliente
     */
    public function cliente(){
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relación uno a muchos.
     * Un producto del inventario puede estar en muchas ubicaciones.
     */
    public function productoPercha(){
        return $this->hasMany(ProductoEnPercha::class);
    }

    /**
     * Relación muchos a muchos.
     * Uno o varios items del inventario estan en un prestamo temporal
     */
    public function detallesPrestamoInventario(){
        return $this->belongsToMany(PrestamoTemporal::class, 'inventario_prestamo_temporal', 'prestamo_id', 'inventario_id')
            ->withPivot('cantidad')
            ->withTimestamps()
            ->using(InventarioPrestamoTemporal::class);
    }

    /******************
     * METODOS
     * ****************
     */
    
}
