<?php

namespace App\Models\Medico;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class ProfesionalSalud extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_profesionales_salud';
    protected $fillable = [
        'codigo',
        'empleado_id'
    ];

    protected $primaryKey = 'empleado_id'; // Especifica que user_id es la clave primaria
    public function getKeyName()
    {
        return 'empleado_id';
    }
    public $incrementing = false;

    private static $whiteListFilter = ['*'];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
