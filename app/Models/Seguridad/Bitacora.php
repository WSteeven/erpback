<?php

namespace App\Models\Seguridad;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class Bitacora extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'seg_bitacoras';
    protected $fillable = [
        'fecha_hora_inicio_turno',
        'fecha_hora_fin_turno',
        'jornada',
        'observaciones',
        'prendas_recibidas_ids',
        'zona_id',
        'agente_turno_id',
        'protector_id',
        'conductor_id',
    ];

    private static array $whiteListFilter = ['*'];

    /**************
     * Relaciones
     **************/
    public function zona() {
        return $this->belongsTo(Zona::class);
    }
    
    public function agenteTurno()
    {
        return $this->belongsTo(Empleado::class, 'agente_turno_id', 'id');
    }

    public function protector()
    {
        return $this->belongsTo(Empleado::class, 'protector_id', 'id');
    }

    public function conductor()
    {
        return $this->belongsTo(Empleado::class, 'conductor_id', 'id');
    }
}
