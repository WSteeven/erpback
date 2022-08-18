<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;
    protected $table = "empleados";
    protected $fillable = [
		'identificacion',
		'nombres',
		'apellidos',
		'fecha_nacimiento',
		'jefe_id',
		'localidad_id',
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
        return $this->belongsTo(User::class);
    }

    // Relacion muchos a muchos
    public function grupos(){
        return $this->belongsToMany(Grupo::class);
    }

    // Relacion uno a uno (inversa)
    public function localidad()
    {
        return $this->belongsTo(Localidad::class);
    }

    // Relacion uno a uno
    public function jefes() {
        return $this->hasOne(Empleado::class, 'jefe_id');
    }
}
