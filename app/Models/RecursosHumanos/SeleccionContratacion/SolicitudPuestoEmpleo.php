<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use App\Models\Autorizacion;
use App\Models\Cargo;
use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class SolicitudPuestoEmpleo extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;
    protected $table = 'rrhh_solicitudes_puestos_empleos';
    // 'nomina y roles de pago';  prefix=rrhh_nomina_
    // 'seleccion y contratacion de personal'; prefix=rrhh_contratacion_
    // 'control de asistencia'; prefix=rrhh_asistencia_
    // 'otros submodulos'; prefix=rrhh_

    /**
     * Este comando sirve para hacer rollback y migrar en un solo paso
     *  las ultimas migraciones hacia atras segun el valor pasado a step
     * 
     * php artisan migrate:refresh --step=3
     * 
     * `Nota` Si no se usa step se ejecutaran todas las migraciones desde cero
     */



    protected $fillable = [
        'nombre',
        'publicada',
        'tipo_puesto_id',
        'autorizador_id',
        'autorizacion_id',
        'cargo_id',
        'anios_experiencia',
        'descripcion',

    ];
    private static $whiteListFilter = ['*'];
    public function tipoPuesto()
    {
        return $this->hasOne(TipoPuestoTrabajo::class, 'id', 'tipo_puesto_id');
    }
    public function cargo()
    {
        return $this->hasOne(Cargo::class, 'id', 'cargo_id');
    }
    public function autorizador()
    {
        return $this->belongsTo(Empleado::class);
    }
    public function autorizacion()
    {
        return $this->hasOne(Autorizacion::class, 'id', 'autorizacion_id');
    }
}
