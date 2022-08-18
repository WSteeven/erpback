<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;
    protected $table = 'modelos';
    protected $fillable = [
        'nombre',
        'marca_id'];

    /* Un modelo pertenece a un producto */
    public function producto()
    {
        return $this->hasOne(Producto::class);
    }

    /* Uno o varios modelos pertenecen a una marca */
    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }
}
