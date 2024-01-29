<?php

namespace App\Models\Ventas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class PagoComision extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'ventas_pagos_comisiones';
    protected $fillable =['fecha_inicio','fecha_fin','vendedor_id','chargeback','valor','pago'];
    private static $whiteListFilter = [
        '*',
    ];
    public function vendedor(){
        return $this->belongsTo(Vendedor::class, 'vendedor_id');
    }
    protected $casts = ['pago'=>'boolean'];
}
