<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    use HasFactory;
    protected $table = 'ubicaciones';
    protected $fillable = ['codigo','percha_id','piso_id'];

    public function percha()
    {
        return $this->belongsTo(Percha::class);
    }

    public function inventario()
    {
        return $this->hasMany(ProductosEnPercha::class);
    }
}
