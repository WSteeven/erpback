<?php

namespace App\Models\Tareas;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class SubcentroCosto extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait, Filterable;
    use AuditableModel;
    protected $table = 'tar_subcentros_costos';
    protected $fillable = [
        'nombre', 'centro_costo_id', 'activo'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo' => 'boolean',
    ];

    private static $whiteListFilter = [
        '*',
    ];

    /**
     * Relacion uno a muchos inversa.
     * Uno o varios subcentros de costos pertenecen a un centro de costos general.
     */
    public function centro()
    {
        return $this->belongsTo(CentroCosto::class, 'centro_costo_id', 'id');
    }
}
