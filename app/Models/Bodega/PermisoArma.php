<?php

namespace App\Models\Bodega;

use App\Models\DetalleProducto;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;


class PermisoArma extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable;
    use AuditableModel;

    protected $table = 'bod_permisos_armas';
    protected $fillable = [
        'nombre',
        'fecha_emision',
        'fecha_caducidad',
        'imagen_permiso',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static $whiteListFilter = [
        '*',
    ];

    /**
     * Relación uno a uno (inversa).
     * Un Permiso de arma puede estar asignado a 0 o 1 DetalleProducto
     */
    public function detalle()
    {
        return $this->belongsTo(DetalleProducto::class);
    }
}
