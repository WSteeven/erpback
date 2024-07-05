<?php

namespace App\Models\Vehiculos;

use App\Models\Archivo;
use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class RegistroIncidente extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait, Filterable;

    protected $table = 'veh_registros_incidentes';
    protected $fillable = [
        'vehiculo_id',
        'fecha',
        'descripcion',
        'tipo',
        'gravedad',
        'persona_reporta_id',
        'persona_registra_id',
        'aplica_seguro',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'aplica_seguro' => 'boolean',
    ];

    private static $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }
    public function personaReporta()
    {
        return $this->belongsTo(Empleado::class, 'persona_reporta_id', 'id');
    }
    public function personaRegistra()
    {
        return $this->belongsTo(Empleado::class, 'persona_registra_id', 'id');
    }

    /**
     * Relacion polimorfica con Archivos uno a muchos.
     *
     */
    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }
}
