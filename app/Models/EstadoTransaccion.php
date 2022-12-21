<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class EstadoTransaccion extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable;
    use AuditableModel;
    
    protected $table = 'estados_transacciones_bodega';
    protected $fillable=['nombre'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];
    const PENDIENTE ='PENDIENTE';
    const COMPLETA ='COMPLETA';
    const PARCIAL ='PARCIAL';
    
    private static $whiteListFilter = [
        '*',
    ];
    
    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    
    public function transacciones()
    {
        return $this->belongsToMany(TransaccionBodega::class, 'tiempo_estado_transaccion','transaccion_id', 'estado_id')
            ->withPivot('observacion')
            ->withTimestamps()
            ->orderByPivot('created_at', 'desc');
    }


    /**
     * Relacion uno a muchos.
     * Un estado esta en varios traspasos
     */
    public function traspasos(){
        return $this->hasMany(Traspaso::class);
    }

    /**
     * Relación uno a uno.
     * Un estado puede estar en un pedido a la vez.
     */
    public function pedido(){
        return $this->hasOne(Pedido::class);
    }



    
}
