<?php

namespace App\Models\Medico;

use App\Models\Canton;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;

class LaboratorioClinico extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'med_laboratorios_clinicos';
    protected $fillable = [
        'nombre',
        'direccion',
        'celular',
        'correo',
        'coordenadas',
        'activo',
        'canton_id',
    ];

    private static $whiteListFilter = ['*'];

    protected $casts = [
        'activo' => 'boolean'
    ];

    /*************
     * Relaciones
     *************/
    public function canton()
    {
        return $this->belongsTo(Canton::class);
    }
}
