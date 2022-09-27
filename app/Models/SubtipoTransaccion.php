<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class SubtipoTransaccion extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    
    protected $table = "subtipos_transacciones";
    protected $fillable = ['nombre', 'tipo_transaccion_id'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    /**
     * Relacion uno a muchos (inversa)
     * Uno o varios subtipos pertenecen a un tipo de transaccion
     */
    public function tipoTransaccion(){
        return $this->belongsTo(TipoTransaccion::class);
    }

    public function transaccion(){
        return $this->hasOne(TransaccionBodega::class);
    }
}
