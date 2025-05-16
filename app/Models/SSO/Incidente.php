<?php

namespace App\Models\SSO;

use App\Models\Archivo;
use App\Models\Devolucion;
use App\Models\Empleado;
use App\Models\Pedido;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @method static create(mixed $datos)
 */
class Incidente extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    // Tipos de incidentes
    const ES_REPORTE_INCIDENTE = 'REPORTE INCIDENTE';
    const ES_CAMBIO_EPP = 'CAMBIO DE EPP';

    // Estados de incidentes
    const CREADO = 'CREADO';
    const EN_CURSO = 'EN CURSO';
    const FINALIZADO = 'FINALIZADO';

    protected $table = 'sso_incidentes';
    protected $fillable = [
        'titulo',
        'descripcion',
        'coordenadas',
        'tipo_incidente',
        'estado',
        'detalles_productos',
        'empleado_reporta_id',
        'empleado_involucrado_id',
        'inspeccion_id',
        'cliente_id',
    ];

    private static array $whiteListFilter = ['*'];

    public function empleadoReporta()
    {
        return $this->belongsTo(Empleado::class, 'empleado_reporta_id');
    }

    public function empleadoInvolucrado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_involucrado_id');
    }

    public function inspeccion()
    {
        return $this->belongsTo(Inspeccion::class);
    }

    public function seguimientoIncidente()
    {
        return $this->hasOne(SeguimientoIncidente::class);
    }

    public function solicitudDescuento()
    {
        return $this->hasOne(SolicitudDescuento::class);
    }

    public function pedido()
    {
        return $this->hasOne(Pedido::class);
    }

    public function devolucion()
    {
        return $this->hasOne(Devolucion::class);
    }

    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }
}
