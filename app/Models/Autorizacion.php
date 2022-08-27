<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autorizacion extends Model
{
    use HasFactory, UppercaseValuesTrait;
    protected $table = "autorizaciones";
    protected $fillable = ["nombre"];
    protected $casts = [
            'created_at' => 'datetime:Y-m-d h:i:s a',
            'updated_at' => 'datetime:Y-m-d h:i:s a',
        ];


    public function transacciones()
    {
        return $this->belongsToMany(TransaccionesBodega::class);
    }
}
