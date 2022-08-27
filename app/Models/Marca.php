<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory, UppercaseValuesTrait;
    protected $table = "marcas";// <-- El nombre personalizado

    protected $fillable = ['nombre'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];


    /* Una marca tiene muchos modelos */
    public function modelos()
    {
        return $this->hasMany(Modelo::class);
    }
}
