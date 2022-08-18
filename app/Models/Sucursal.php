<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory;
    protected $table = "sucursales";

    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }

    public function perchas()
    {
        return $this->hasMany(Percha::class);
    }
}
