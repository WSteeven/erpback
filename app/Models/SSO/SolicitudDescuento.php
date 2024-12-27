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
class SolicitudDescuento extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    // Estados de solicitudes de descuentos
    const CREADO = 'CREADO';
    const PRECIOS_ESTABLECIDOS = 'PRECIOS ESTABLECIDOS';
    const DESCONTADO = 'DESCONTADO';

    protected $table = 'sso_solicitudes_descuentos';
    protected $fillable = [
        'titulo',
        'descripcion',
        'estado',
        'detalles_productos',
        'empleado_involucrado_id',
        'empleado_solicitante_id',
        'cliente_id',
        'incidente_id',
    ];

    private static array $whiteListFilter = ['*'];

    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }

    public function empleadoInvolucrado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_involucrado_id');
    }

    public function empleadoSolicitante()
    {
        return $this->belongsTo(Empleado::class, 'empleado_solicitante_id');
    }

    public function incidente()
    {
        return $this->belongsTo(Incidente::class, 'incidente_id');
    }
}
