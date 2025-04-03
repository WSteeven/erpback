<?php

namespace App\Models\SSO;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * @method static create(mixed $datos)
 */
class CertificacionEmpleado extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'sso_certificaciones_empleados';
    protected $fillable = [
        'empleado_id',
        'certificaciones_id',
    ];

    private static array $whiteListFilter = ['*'];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    public function certificaciones()
    {
        return Certificacion::whereIn('id', json_decode($this['certificaciones_id']))->get();
    }
}
