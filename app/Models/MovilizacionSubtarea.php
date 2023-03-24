<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class MovilizacionSubtarea extends Model
{
    use HasFactory, Filterable;

    public $timestamps = false;
    protected $table = "movilizacion_subtarea";
    protected $fillable = [
        'fecha_hora_salida',
        'fecha_hora_llegada',
        'empleado_id',
        'subtarea_id'
    ];

    private static $whiteListFilter = [
        '*'
    ];

    public function empleado() {
        return $this->belongsTo(Empleado::class);
    }

    public function subtarea() {
        return $this->belongsTo(Subtarea::class);
    }
}
