<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaccionesBodega extends Model
{
    use HasFactory;
    public $table = 'transacciones_bodega';
    public $fillable = [
        'justificacion',
        'fecha_limite',
        'solicitante_id',
        'tipo_id',
        'sucursal_id',
        'per_autoriza_id',
        'per_entrega_id',
        'lugar_destino',
    ];


    /* Una transaccion tiene varios estados de autorizacion durante su ciclo de vida */
    public function autorizaciones()
    {
        //return $this->belongsToMany(Autorizacion::class, 'autorizacion_transaccion_pivote');
        return $this->belongsToMany(Autorizacion::class, 'tiempo_autorizaciones');
    }

    /* Una transaccion tiene varios estados durante su ciclo de vida */
    public function estados()
    {
        return $this->belongsToMany(EstadosTransaccionesBodega::class, 'tiempo_estado_transacciones');
    }

    //Una transaccion tiene varios productos solicitados
    public function productos()
    {
        return $this->belongsToMany(NombreProducto::class);
    }

    /* Una o varias transacciones pertenece a un solicitante */
    public function solicitante()
    {
        return $this->belongsTo(User::class);
    }

    /* Una o varias transacciones tienen un solo tipo de transaccion*/
    public function tipoTransaccion()
    {
        return $this->hasOne(TipoTransaccion::class);
    }

}
