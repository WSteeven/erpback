<?php

namespace App\Models\FondosRotativos\Saldo;

use App\Models\Empleado;
use App\Models\FondosRotativos\Gasto\EstadoViatico;
use App\Models\Notificacion;
use App\Models\Tarea;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class SaldosFondosRotativos extends Model  implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;
    protected $table = 'fr_saldos_fondos_rotativos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'fecha',
        'saldo_anterior',
        'saldo_depositado',
        'saldo_actual',
        'tipo_saldo',
        'empleado_id',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'usuario_envia_id');
    }

    public function saldoable()
    {
        return $this->morphTo();
    }
}
