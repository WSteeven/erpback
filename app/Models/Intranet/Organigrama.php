<?php

namespace App\Models\Intranet;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Organigrama extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait, Filterable;

    protected $table = 'intra_organigrama';

    protected $fillable = [
        'empleado_id',
        'cargo',
        'jefe_id',
        'departamento',
        'nivel',
        'tipo',
    ];

    private static array $whiteListFilter = [
        '*',
    ];

    /**
     * Relación con el modelo Empleado para obtener el empleado correspondiente.
     */
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    /**
     * Relación autorreferenciada para obtener el jefe inmediato dentro del organigrama.
     */
    public function jefe()
    {
        return $this->belongsTo(self::class, 'jefe_id');
    }

    /**
     * Relación inversa para obtener los subordinados de un jefe inmediato.
     */
    public function subordinados()
    {
        return $this->hasMany(self::class, 'jefe_id');
    }
}
