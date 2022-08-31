<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoTarea extends Model
{
    use HasFactory;

    protected $table = "tipos_tareas";
    protected $fillable = ['nombre', 'cliente_id'];

    // Relacion uno a muchos (inversa)
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
