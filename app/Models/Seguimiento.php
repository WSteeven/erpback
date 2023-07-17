<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class Seguimiento extends Model implements Auditable
{
    use HasFactory, AuditableModel, UppercaseValuesTrait, Filterable;

    // Seguimiento subtarea
    protected $table = 'seguimientos';

    protected $fillable = [
        'observaciones',
        'materiales_tarea_ocupados',
        'materiales_stock_ocupados',
        'materiales_devolucion',
    ];

    protected $casts = [
        'observaciones' => 'json',
        'materiales_tarea_ocupados' => 'json',
        'materiales_stock_ocupados' => 'json',
        'materiales_devolucion' => 'json',
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static $whiteListFilter = ['*'];

    public function trabajoRealizado()
    {
        return $this->hasMany(TrabajoRealizado::class);
    }

    // Relacion uno a muchos
    public function archivos()
    {
        return $this->hasMany(ArchivoSeguimiento::class);
    }
}
