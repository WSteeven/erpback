<?php

namespace App\Models;

use App\Models\ComprasProveedores\CategoriaOfertaProveedor;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class Departamento extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    const DEPARTAMENTO_SSO = 5;
    const DEPARTAMENTO_CONTABILIDAD = 6;

    protected $table = 'departamentos';
    protected $fillable = ['nombre', 'activo', 'responsable_id'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo' => 'boolean',
    ];

    private static $whiteListFilter = [
        'nombre',
        'activo',
        'responsable_id',
    ];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    public function responsable()
    {
        return $this->belongsTo(Empleado::class, 'responsable_id', 'id');
    }
    public function calificaciones_proveedores()
    {
        return $this->belongsToMany(Proveedor::class, 'detalle_departamento_proveedor', 'departamento_id', 'proveedor_id')
            ->withPivot(['calificacion', 'fecha_calificacion'])
            ->withTimestamps();
    }
    public function categorias_proveedores()
    {
        return $this->belongsToMany(CategoriaOfertaProveedor::class, 'cmp_detalle_categoria_departamento_proveedor', 'departamento_id', 'categoria_id')
            ->withTimestamps();
    }
}
