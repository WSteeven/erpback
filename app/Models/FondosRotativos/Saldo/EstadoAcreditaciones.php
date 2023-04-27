<?php

namespace App\Models\FondosRotativos\Saldo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class EstadoAcreditaciones extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    const REALIZADO = 1;
    const ANULADO = 2;

    protected $table = 'estado_acreditaciones';
    protected $primaryKey = 'id';
    protected $fillable = [
        'estado',
    ];
    private static $whiteListFilter = [
        'estado',
    ];
}
