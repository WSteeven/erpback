<?php

namespace App\Models\FondosRotativos\Gasto;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class TipoFondo extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    //use AuditableModel;
    protected $table = 'tipo_fondo';
    protected $primaryKey = 'id';
    protected $fillable = [
        'descripcion',
        'transcriptor',
        'fecha_trans',
    ];
    private static $whiteListFilter = [
        'descripcion',
    ];
}
