<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class TipoTrabajo extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, Filterable;

    protected $table = "tipos_trabajos";
    protected $fillable = [
        'nombre',
        'cliente_id',
        // 'imagenes_adicionales',
        //'campos_adicionales'
    ];
    private static $whiteListFilter = [
        '*',
    ];

    // Relacion uno a muchos (inversa)
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
