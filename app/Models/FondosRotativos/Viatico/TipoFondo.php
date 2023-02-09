<?php

namespace App\Models\FondosRotativos\Viatico;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoFondo extends Model
{
    use HasFactory;
    use Filterable;
    //use AuditableModel;
    protected $table = 'tipo_fondo';
    protected $primaryKey = 'id';
}
