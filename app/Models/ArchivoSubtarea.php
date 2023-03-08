<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class ArchivoSubtarea extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable;
    protected $table = 'archivos_subtareas';
    protected $fillable = ['nombre', 'ruta', 'tamanio_bytes', 'comentario', 'subtarea_id'];

    private static $whiteListFilter = ['*'];
}
