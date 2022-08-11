<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piso extends Model
{
    use HasFactory;
    protected $table = "pisos";

    public function perchas()
    {
        return $this->belongsToMany(Percha::class);
    }
}
