<?php

namespace App\Models\FondosRotativos\Saldo;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class ValorAcreditar extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;
    protected $table = 'fr_valor_acreditar';
    protected $primaryKey = 'id';
    protected $fillable = [
        'empleado_id',
        'acreditacion_semana_id',
        'monto_generado',
        'monto_modificado'
    ];
    private static $whiteListFilter = [
        'empleado_id',
        'acreditacion_semana_id',
        'monto_generado',
        'monto_modificado'
    ];
    public function empleado()
    {
        return $this->hasOne(Empleado::class, 'id', 'empleado_id');
    }
    public function acreditacion_semanal()
    {
        return $this->hasOne(AcreditacionSemana::class, 'id', 'empleado_id');
    }
}
