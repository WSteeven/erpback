<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Sucursal extends Model implements Auditable
{
    use HasFactory;
    //use UppercaseValuesTrait;
    use AuditableModel;
    use Filterable;

    protected $table = "sucursales";
    protected $fillable = ['lugar', 'telefono','extension', 'correo'];
    // protected $fillable = ['lugar', 'telefono', 'correo', 'administrador_id'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static $whiteListFilter=['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

    /**
     * Relacion uno a muchos
     * Obtener los control de stock para una sucursal 
     */
    public function control_stocks()
    {
        return $this->hasOne(ControlStock::class);
    }

    /**
     * Relación uno a muchos.
     * Una sucursal tiene muchos empleados
     */
    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }

    /**
     * Relacion uno a muchos
     * Una sucursal tiene muchas perchas
     */
    public function perchas()
    {
        return $this->hasMany(Percha::class);
    }
    /**
     * Relacion uno a uno
     * Una sucursal tiene muchas inventarios
     */
    public function inventarios()
    {
        return $this->hasOne(Inventario::class);
    }

    /**
     * Relación uno a muchos.
     * Una sucursal tiene uno o muchos activos fijos.
     */
    public function activos()
    {
        return $this->hasMany(ActivoFijo::class);
    }

    /**
     * Relacion uno a muchos.
     * En una sucursal se realizan varias transacciones
     */
    public function transacciones(){
        return $this->hasMany(TransaccionBodega::class);
    }

    /**
     * Relacion uno a muchos.
     * En una sucursal se realizan varias devoluciones
     */
    public function devoluciones(){
        return $this->hasMany(Devolucion::class);
    }
    /**
     * Relacion uno a muchos.
     * En una sucursal se realizan varios traspasos
     */
    public function traspasos(){
        return $this->hasMany(Traspaso::class);
    }

    /**
     * Relación uno a muchos .
     * Una sucursal puede uno o varios pedidos
     */
    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
    
    /**
     * Relación uno a muchos .
     * Una sucursal puede tener una o varias transferencias
     */
    public function transferencias()
    {
        return $this->hasMany(Transferencia::class);
    }
    /**
     * Relación uno a uno.
     * Una sucursal tiene un adminitrador
     */
    public function administrador(){
        return $this->belongsTo(User::class);
    }
}
