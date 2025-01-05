<?php

namespace App\Models\SSO;

use App\Models\Medico\ConsultaMedica;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class ConsultaMedicaSeguimientoAccidente extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'sso_consulta_medica_seguimiento_accidente';
    protected $fillable = [
        'certificado_alta',
        'observacion_alta',
        'restricciones',
        'seguimiento_accidente_id',
        'consulta_medica_id',
    ];

    private static array $whiteListFilter = ['*'];

    public function seguimientoAccidente()
    {
        return $this->belongsTo(SeguimientoAccidente::class);
    }

    public function consultaMedica()
    {
        return $this->belongsTo(ConsultaMedica::class);
    }
}
