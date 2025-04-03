<?php

namespace App\Models\FondosRotativos\Gasto;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class AutorizadorDirecto extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable, UppercaseValuesTrait;

    protected $table = 'fr_autorizadores_directos_gastos';
    protected $fillable = [
        'empleado_id',
        'autorizador_id',
        'observacion',
        'activo'
    ];

    private static array $whiteListFilter = ['*'];

    public function empleado(){
        return $this->belongsTo(Empleado::class);
    }

    public function autorizador(){
        return $this->belongsTo(Empleado::class);
    }
}
