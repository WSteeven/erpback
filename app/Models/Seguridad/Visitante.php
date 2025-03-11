<?php

namespace App\Models\Seguridad;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class Visitante extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'seg_visitantes';
    protected $fillable = [
        'nombre_completo',
        'identificacion',
        'celular',
        'motivo_visita',
        'persona_visitada',
        'placa_vehiculo',
        'observaciones',
        'actividad_bitacora_id',
    ];

    private static array $whiteListFilter = ['*'];

    public function personaVisitada()
    {
        return $this->belongsTo(Empleado::class, 'persona_visitada');
    }
}
