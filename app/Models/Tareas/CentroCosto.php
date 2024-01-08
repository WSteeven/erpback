<?php

namespace App\Models\Tareas;

use App\Models\Cliente;
use App\Models\Tarea;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class CentroCosto extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait, Filterable, Searchable;

    protected $table = 'tar_centros_costos';
    protected $fillable = ['nombre', 'cliente_id', 'activo'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo' => 'boolean',
    ];

    private static $whiteListFilter = [
        '*',
    ];

    /**
     * Relación uno a muchos.
     * Un centro de costos pertenece a una o varias tareas.
     */
    public function tareas()
    {
        return $this->hasMany(Tarea::class);
    }

    /**
     * Relación uno a uno.
     * Un centro de costos pertenece a un cliente.
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relación uno a muchos.
     * Un centro de costos tiene uno o más subcentros de costos.
     */
    public function subcentros()
    {
        return $this->hasMany(SubcentroCosto::class);
    }
}
