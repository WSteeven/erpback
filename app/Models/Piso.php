<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piso extends Model
{
    use HasFactory, UppercaseValuesTrait;
    protected $table = 'pisos';
    protected $fillable = ['piso','columna'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];


    public function perchas()
    {
        return $this->belongsToMany(Percha::class);
    }
}
