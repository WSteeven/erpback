<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory, UppercaseValuesTrait;
    protected $table = "sucursales";
    protected $fillable = ['lugar','telefono','correo'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];


    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }

    public function perchas()
    {
        return $this->hasMany(Percha::class);
    }
}
