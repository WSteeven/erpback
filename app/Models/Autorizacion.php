<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autorizacion extends Model
{
    use HasFactory;
    protected $table = "autorizaciones";
    protected $fillable = ["nombre"];

    public function transacciones()
    {
        return $this->belongsToMany(TransaccionesBodega::class);
    }
}
