<?php

namespace App\Models\Appenate;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class Progresiva extends Model implements Auditable
{
    use HasFactory;
    use Filterable;
    use AuditableModel;

    protected $table = 'appenate_progresivas';
    protected $fillable = [
        'metadatos',
        'filename',
        'proyecto',
        'ciudad',
        'enlace',
        'fecha_instalacion',
        'cod_bobina',
        'mt_inicial',
        'mt_final',
        'fo_instalada',
        'num_tarea',
        'hilos',
        'responsable',
    ];
    protected $casts = [
        'metadatos'=>'json',
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static array $whiteListFilter = ['*'];

    public function registros()
    {
        return $this->hasMany(RegistroProgresiva::class);
    }

}
