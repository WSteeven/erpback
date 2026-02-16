<?php

namespace App\Models\Tareas;

use App\Models\TipoTrabajo;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class TipoFotografia extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable, UppercaseValuesTrait;
    protected $table = 'tar_tipos_fotografias';
    protected $fillable = [
        'nombre',
        'tipo_trabajo_id',
        'activo',
    ];

    protected $casts = ['activo'=>'boolean'];

    public static array $whiteListFilter = ['*'];

    public function tipoTrabajo()
    {
        return $this->belongsTo(TipoTrabajo::class, 'tipo_trabajo_id');
    }

}
