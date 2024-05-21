<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use App\Models\Autorizacion;
use App\Models\Cargo;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class SolicitudPuestoEmpleo extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'rrhh_solicitudes_puestos_empleos';
    protected $fillable = [
        'descripcion',
        'anos_experiencia',
        'tipo_puesto_id',
        'cargo_id',
        'autorizacion_id'

    ];
    private static $whiteListFilter = [
        'descripcion',
        'anos_experiencia',
        'tipo_puesto_id',
        'tipo_puesto',
        'cargo_id',
        'cargo',
        'autorizacion_id',
        'autorizacion'
    ];
    public function tipoPuesto(){
        return $this->hasOne(TipoPuestoTrabajo::class,'id', 'tipo_puesto_id');
    }
    public function cargo(){
        return $this->hasOne(Cargo::class,'id', 'cargo_id');
    }
    public function autorizacion(){
        return $this->hasOne(Autorizacion::class,'id', 'autorizacion_id');
    }
}
