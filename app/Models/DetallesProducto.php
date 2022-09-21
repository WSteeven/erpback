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
     * Relacion uno a muchos 
     * Un producto puede estar en muchos inventarios 
     */
    public function inventarios()
    {
        return $this->hasMany(Inventario::class);
    }

    /* Uno o mas detalles de productos pertenecen a un producto en general */
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function tipo_fibra(){
        return $this->belongsTo(TipoFibra::class);
    }
    public function hilo(){
        return $this->belongsTo(Hilo::class);
    }

    /**
     * Relacion uno a muchos
     * Obtener los control de stock para un detalle 
     */
    public function control_stocks()
    {
        return $this->hasMany(ControlStock::class);
    }

    /**
     * Relacion uno a uno (inversa)
     * Un producto tiene un solo modelo
     */
    public function modelo()
    {
        return $this->belongsTo(Modelo::class);
    }
}
