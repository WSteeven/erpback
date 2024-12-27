<?php

namespace App\Models\SSO;

use App\Models\Archivo;
use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @method static create(mixed $datos)
 */
class Inspeccion extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    // Estados de incidentes
    const CREADO = 'CREADO';
    const FINALIZADO = 'FINALIZADO';

    protected $table = 'sso_inspecciones';
    protected $fillable = [
        'titulo',
        'descripcion',
        'fecha_inicio',
        'estado',
        'tiene_incidencias',
        'coordenadas',
        'seguimiento',
        'responsable_id',
        'empleado_involucrado_id',
    ];

    private static array $whiteListFilter = ['*'];

    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }

    public function responsable()
    {
        return $this->belongsTo(Empleado::class, 'responsable_id', 'id');
    }

    public function empleadoInvolucrado()
    {
        return $this->belongsTo(Empleado::class, 'responsable_id', 'id');
    }

    public function incidentes()
    {
        return $this->hasMany(Incidente::class);
    }
}
