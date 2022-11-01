<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UbicacionTarea extends Model
{
    use HasFactory;
    protected $table = 'ubicaciones_tareas';
    protected $fillable = [
        'parroquia',
        'direccion',
        'referencias',
        'coordenadas',
        'provincia_id',
        'canton_id',
        'tarea_id',
    ];

    public function provincia()
    {
        return $this->belongsTo(Provincia::class);
    }

    public function canton()
    {
        return $this->belongsTo(Canton::class);
    }
}
