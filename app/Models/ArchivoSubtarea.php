<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UppercaseValuesTrait;

class ArchivoSubtarea extends Model
{
    use HasFactory, UppercaseValuesTrait;
    protected $table = 'archivos_subtareas';
    protected $fillable = ['nombre', 'ruta', 'tamanio_bytes', 'comentario', 'subtarea_id'];
}
