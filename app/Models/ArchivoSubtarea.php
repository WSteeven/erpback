<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchivoSubtarea extends Model
{
    use HasFactory;
    protected $table = 'archivos_subtareas';
    protected $fillable = ['nombre', 'ruta', 'tamanio_bytes', 'comentario', 'subtarea_id'];
}
