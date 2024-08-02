<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Modalidad extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;

    protected $table = 'rrhh_contratacion_modalidades';
    protected $fillable = [
      'nombre',
      'activo'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo'=>'boolean',
    ];

    private static array $whiteListFilter = [
        '*',
    ];





}
