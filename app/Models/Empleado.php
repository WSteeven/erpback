<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Empleado extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    protected $table = "empleados";
    protected $fillable = [
		'identificacion',
		'nombres',
		'apellidos',
		'telefono',
		'fecha_nacimiento',
		'jefe_id',
		'sucursal_id',
		'estado',
		'rol',
	];

    const ACTIVO = 'ACTIVO';
    const INACTIVO = 'INACTIVO';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];


    //Relacion uno a muchos polimorfica
    public function telefonos(){
        return $this->morphMany('App\Models\Telefono','telefonable');
    }

    /**
     * Obtiene el usuario que posee el perfil.
     */
    // Relacion uno a uno (inversa)
    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'id');
    }

    // Relacion muchos a muchos
    public function grupos(){
        return $this->belongsToMany(Grupo::class);
    }

    /**
     * Relación uno a muchos (inversa).
     * Uno o más empleados pertenecen a una sucursal.
     */
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    // Relacion uno a uno
    public function jefe() {
        return $this->belongsTo(Empleado::class, 'jefe_id');
    }

    /**
     * Relacion uno a muchos
     * Un empleado es solicitante de varias transacciones
     */
    public function transacciones()
    {
        return $this->hasMany(TransaccionBodega::class);
    }

    /**
     * Relacion uno a muchos.
     * Un empleado con rol superior o igual a COORDINADOR puede autorizar todas las transacciones de sus empleados a cargo
     */
    public function autorizadas(){
        return $this->hasMany(TransaccionBodega::class);
    }

    /**
     * Relacion uno a muchos.
     * Un empleado con rol BODEGA puede entregar cualquier transaccion 
     */
    public function atendidas(){
        return $this->hasMany(TransaccionBodega::class);
    }
}

