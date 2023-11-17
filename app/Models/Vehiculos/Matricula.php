<?php

namespace App\Models\Vehiculos;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Matricula extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, Filterable, UppercaseValuesTrait;


    protected $table = 'veh_matriculas';
    protected $fillable = [
        'vehiculo_id',
        'fecha_matricula',
        'proxima_matricula',
        'matriculador',
        'matriculado',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'matriculado' => 'boolean',
    ];

    private static $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    /**
     * Relación uno a muchos (inversa).
     * Una o varias multas pertenecen a un Conductor.
     */
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }
}
