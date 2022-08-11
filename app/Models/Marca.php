<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;
    protected $table = "marcas";// <-- El nombre personalizado

    /* Una marca tiene muchos modelos */
    public function modelos()
    {
        return $this->hasMany(Modelo::class);
    }
}
