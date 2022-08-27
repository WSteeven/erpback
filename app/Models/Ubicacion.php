<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    use HasFactory, UppercaseValuesTrait;
    protected $table = 'ubicaciones';
    protected $fillable = ['codigo','percha_id','piso_id'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];


    public function percha()
    {
        return $this->belongsTo(Percha::class);
    }

    public function inventario()
    {
        return $this->hasMany(ProductosEnPercha::class);
    }
}
