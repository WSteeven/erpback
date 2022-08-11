<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaccionesBodega extends Model
{
    use HasFactory;
    public $table = 'transacciones_bodega';

    /* Una transaccion tiene varios estados de autorizacion durante su ciclo de vida */
    public function autorizaciones()
    {
        return $this->belongsToMany(Autorizacion::class);
    }

    /* Una transaccion tiene varios estados durante su ciclo de vida */
    public function estados()
    {
        return $this->belongsToMany(EstadosTransaccionesBodega::class);
    }

    /* Una o varias transacciones pertenece a un solicitante */
    public function solicitante()
    {
        return $this->belongsTo(Empleado::class);
    }

    /* Una o varias transacciones tienen un solo tipo de transaccion*/
    public function tipoTransaccion()
    {
        return $this->hasOne(TipoTransaccion::class);
    }

    /* Una transaccion tiene un medio de activacion */
    public function medio()
    {
        return $this->hasOne(Medio::class);
    }
}
