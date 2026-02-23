<?php

namespace App\Models\Tareas;

use App\Models\Subtarea;
use App\Traits\UppercaseValuesTrait;
use Arr;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Coordenadas extends Model implements Auditable
{
    use HasFactory, AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;

    protected $table = 'tar_coordenadas';
    protected $fillable = [
        'subtarea_id', //relation on subtareas
        'tipo_id', // relation on tar_tipos_coordenadas
        'nombre', //string
        'latitud',
        'longitud',
        'direccion', // nullable
        'observacion', //nullable
    ];

    private static array $whiteListFilter = [
        '*',
    ];

    public function subtarea()
    {
        return $this->belongsTo(Subtarea::class, 'subtarea_id');
    }

    public function tipo()
    {
        return $this->belongsTo(TipoCoordenada::class, 'tipo_id');
    }

}

