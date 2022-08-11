<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Localidad extends Model
{
    use HasFactory;
    protected $table = "localidades";

    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }

    public function perchas()
    {
        return $this->hasMany(Percha::class);
    }
}
