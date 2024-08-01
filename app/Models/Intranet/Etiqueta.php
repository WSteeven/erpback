<?php

namespace App\Models\Intranet;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;


class Etiqueta extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;

    protected $table = 'intra_etiquetas';
    protected $fillable = [
        'categoria_id',
        'nombre',
        'activo'
    ];

    private static array $whiteListFilter = [
        '*',
    ];

    public function categoria(){
        return $this->belongsTo(CategoriaNoticia::class);
    }
    
}
