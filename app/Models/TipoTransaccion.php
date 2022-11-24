<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class TipoTransaccion extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    
    protected $table="tipos_transacciones";
    protected $fillable = ['nombre'];
    
    const INGRESO = 'INGRESO';
    const EGRESO = 'EGRESO';
    const TRANSFERENCIA = 'TRANSFERENCIA';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    /**
     * Relacion uno a muchos
     * Un tipo de transaccion tiene varios subtipos
     */
    public function subtipos(){
        return $this->hasMany(SubtipoTransaccion::class);
    }
}
