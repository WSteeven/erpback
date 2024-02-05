<?php

namespace App\Models\Ventas;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class CortePagoComision extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait, Filterable;

    protected $table = 'ventas_cortes_pagos_comisiones';
    protected $fillable = [
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];

    const PENDIENTE = 'PENDIENTE';
    const COMPLETA = 'COMPLETA';
    const ANULADA = 'ANULADA';

    private static $whiteListFilter = [
        '*',
    ];

    public function detalles()
    {
        return $this->hasMany(DetallePagoComision::class, 'corte_id');
    }
}
