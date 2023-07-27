<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Autorizacion;
use App\Models\Empleado;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class SolicitudPrestamoEmpresarial extends  Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'solicitud_prestamo_empresarial';
    protected $fillable = [
        'solicitante',
        'fecha',
        'monto',
        'periodo_id',
        'valor_utilidad',
        'plazo',
        'motivo',
        'observacion',
        'foto',
        'estado'
    ];
    public function estado_info()
    {
        return $this->hasOne(Autorizacion::class, 'id', 'estado');
    }
    public function empleado_info()
    {
        return $this->hasOne(Empleado::class, 'id', 'solicitante');
    }
    public function periodo_info(){
        return $this->hasOne(Periodo::class, 'id', 'periodo_id');
    }

    private static $whiteListFilter = [
        'id',
        'solicitante',
        'fecha',
        'monto',
        'periodo',
        'valor_utilidad',
        'plazo',
        'motivo',
        'observacion',
        'foto',
        'estado'
    ];
}
