<?php

namespace App\Models\RecursosHumanos;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class Planificador extends Model implements Auditable
{
    use HasFactory, Filterable, UppercaseValuesTrait, AuditableModel;

    protected $table = 'rrhh_planificadores';
    protected $fillable = [
        'empleado_id',
        'nombre',
        'completado',
        'actividades',
    ];

    protected $casts = [
        'actividades' => 'array',
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
