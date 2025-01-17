<?php

namespace App\Models\ControlPersonal;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Marcacion extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;

    protected $table = 'rrhh_cp_marcaciones';
    protected $fillable = [
        'empleado_id',
        'fecha',
        'marcaciones',
    ];
    protected $casts=[
      'marcaciones' => 'array',
    ];

    private static array $whiteListFilter = [
        '*',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
