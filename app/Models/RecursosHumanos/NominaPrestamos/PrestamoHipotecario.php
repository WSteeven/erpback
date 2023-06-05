<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class PrestamoHipotecario extends Model
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'prestamo_quirorafario';
    protected $fillable = [
        'mes','empleado_id','nut','valor'
    ];
    protected $casts = [
        'valor' => 'decimal:2'
    ];
    private static $whiteListFilter = [
        'id',
        'empleado',
        'mes',
        'nut',
        'valor',
    ];
}
