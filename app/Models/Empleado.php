<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;
    protected $table = "empleados";


    /**
     * Obtiene el usuario que posee el perfil.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function grupos(){
        return $this->belongsToMany(Grupo::class);
    }
    public function localidad()
    {
        return $this->belongsTo(Localidad::class);
    }
}
