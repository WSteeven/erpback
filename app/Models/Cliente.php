<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    protected $table = "clientes";

    public function parroquia()
    {
        return $this->belongsTo(Parroquia::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function nombres()
    {
        return $this->belongsToMany(NombreProducto::class);
    }
}
