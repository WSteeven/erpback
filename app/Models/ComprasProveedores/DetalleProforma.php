<?php

namespace App\Models\ComprasProveedores;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class DetalleProforma extends Model implements Auditable
{
    use HasFactory, AuditableModel, UppercaseValuesTrait, Filterable;

    protected $table = 'cmp_detalles_proformas';
    protected $fillable = [
        'nombre',
        'precio_unitario',
        'facturable',
        'grava_iva',
        'estado',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static $whiteListFilter = [
        '*',
    ];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    
}
