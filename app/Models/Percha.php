<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Percha extends Model
{
    use HasFactory;
    protected $table = 'perchas';
    protected $fillable = ['nombre', 'sucursal_id'];

    public function localidad()
    {
        return $this->belongsTo(Localidad::class);
    }

    public function pisos()
    {
        return $this->belongsToMany(Piso::class);
    }

    public function ubicaciones()
    {
        return $this->hasMany(Ubicacion::class);
    }
}
