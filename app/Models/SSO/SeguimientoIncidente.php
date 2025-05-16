<?php

namespace App\Models\SSO;

use App\Models\Devolucion;
use App\Models\Pedido;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @method static create(array $array)
 */
class SeguimientoIncidente extends Model implements Auditable
{
    use HasFactory, Filterable, AuditableModel;

    protected $table = 'sso_seguimiento_incidentes';
    protected $fillable = [
        'causa_raiz',
        'acciones_correctivas',
        'devolucion_id',
        'pedido_id',
        'solicitud_descuento_id',
        'incidente_id',
    ];

    private static array $whiteListFilter = ['*'];

    public function devolucion()
    {
        return $this->belongsTo(Devolucion::class);
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function solicitudDescuento()
    {
        return $this->belongsTo(SolicitudDescuento::class);
    }

    public function incidente()
    {
        return $this->belongsTo(Incidente::class);
    }
}
