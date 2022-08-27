<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory, UppercaseValuesTrait;
    protected $table = 'modelos';
    protected $fillable = [
        'nombre',
        'marca_id'
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

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
