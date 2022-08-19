<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadosTransaccion extends Model
{
    use HasFactory;
    protected $table = 'estados_transacciones_bodega';
    protected $fillable=['nombre'];

    public function transacciones()
    {
        return $this->belongsToMany(TransaccionBodega::class);
    }
}
