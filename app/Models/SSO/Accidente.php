<?php

namespace App\Models\SSO;

use App\Models\Archivo;
use App\Models\Empleado;
use App\Models\Medico\CitaMedica;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @method static create($datos)
 */
class Accidente extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    // Estados de accidentes
    const CREADO = 'CREADO';
    const FINALIZADO = 'FINALIZADO';

    protected $table = 'sso_accidentes';
    protected $fillable = [
        'titulo',
        'descripcion',
        'medidas_preventivas',
        'empleados_involucrados',
        'fecha_hora_ocurrencia',
        'coordenadas',
        'consecuencias',
        'lugar_accidente',
        'estado',
        'empleado_reporta_id',
    ];

    private static array $whiteListFilter = ['*'];

    public function empleadoReporta()
    {
        return $this->belongsTo(Empleado::class, 'empleado_reporta_id');
    }

    public function seguimientoAccidente()
    {
        return $this->hasOne(SeguimientoAccidente::class);
    }

    public function citasMedicas()
    {
        return $this->hasMany(CitaMedica::class);
    }

    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }
}
