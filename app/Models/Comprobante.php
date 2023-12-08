<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
class Comprobante extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    protected $table = 'comprobantes';
    protected $fillable = [
        'transaccion_id',
        'firmada',
        'estado',
        'observacion',
    ];

    //obtener la llave primaria
    public function getKeyName()
    {
        return 'transaccion_id';
    }

    /**
     * Relación uno a uno.
     * Un comprobante se emite para un egreso.
     */
    public function transaccion(){
        return $this->belongsTo(TransaccionBodega::class, 'transaccion_id');
    }

}
