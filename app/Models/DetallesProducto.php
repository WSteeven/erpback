<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class DetallesProducto extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    
    protected $table = "detalles_productos";
    // estado
    const ACTIVO = "ACTIVO";
    const INACTIVO = "INACTIVO";


    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'producto_id',
        'descripcion',
        'modelo_id',
        'serial',
        'precio_compra',
        'tipo_fibra_id',
        'hilo_id',
        'punta_a',
        'punta_b',
        'punta_corte',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    /**
     * Comprobar si un detalle es fibra
     * @param $id DetalleProducto
     * @return boolean
     */
    public static function comprobarFibra($id){
        $detalle = DetallesProducto::find($id);
        if($detalle->tipo_fibra||$detalle->punta_b){
            return true;
        }else{
            return false;
        }
    }

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    /**
     * Relacion uno a muchos.
     * Un detalle de producto puede estar en muchos inventarios.
     */
    public function inventarios()
    {
        return $this->hasMany(Inventario::class);
    }

    /**
     * Relacion uno a muchos (inversa).
     * Uno o mas detalles de productos pertenecen a un producto en general 
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Relacion uno a uno (inversa).
     * Un detalle de producto tiene 0 o 1 tipo de fibra
     */
    public function tipo_fibra(){
        return $this->belongsTo(TipoFibra::class);
    }

    /**
     * Relacion uno a uno (inversa).
     * Un detalle de producto tiene 0 o 1 hilo
     */
    public function hilo(){
        return $this->belongsTo(Hilo::class);
    }

    /**
     * Relacion uno a muchos.
     * Un detalle de producto tiene un control de stock diferente para cada sucursal.
     * Obtener los control de stock para un detalle. 
     */
    public function control_stocks()
    {
        return $this->hasMany(ControlStock::class);
    }

    /**
     * Relacion uno a uno (inversa).
     * Un detalle de producto tiene 1 y solo 1 modelo
     */
    public function modelo()
    {
        return $this->belongsTo(Modelo::class);
    }

    /**
     * Relacion uno a muchos.
     * Un detalle de producto tiene varias imagenes.
     * Generalmente solo dos, pero queda la posibilidad de que sean más en un futuro.
     */
    public function imagenes(){
        return $this->hasMany(ImagenesProducto::class);
    }
}
