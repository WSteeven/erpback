<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class TransaccionesBodega extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    
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
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
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
        return $this->belongsToMany(Producto::class);
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
