<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use App\Models\Cargo;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Conocimiento extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'rrhh_contratacion_conocimientos';
    protected $fillable = [
        'cargo_id',
        'nombre',
        'activo'
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo' => 'boolean',
    ];

    private static $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    /**
     * Relación uno a muchos (inversa)
     * Un conocimiento pertenece a un cargo y un cargo tiene varios conocimientos.
     */
    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }
}
