<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;
    protected $table = 'grupos';
    protected $fillable = ['nombre', 'creador_id'];

    public function empleados()
    {
        return $this->belongsToMany(Empleado::class);
    }

    public function tareas(){
        return $this->belongsToMany(Tarea::class);
    }

}
