<?php

namespace App\Models\Intranet;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;


class Noticia extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait, Filterable;

    protected $table = 'intra_noticias';
    protected $fillable = [
        'titulo',
        'descripcion',
        'autor_id',
        'categoria_id',
        'etiquetas',
        'imagen_noticia',
        'fecha_vencimiento',
    ];

    private static array $whiteListFilter = [
        '*',
    ];

    public function autor(){
        return $this->belongsTo(Empleado::class);
    }

    public function categoria(){
        return $this->belongsTo(CategoriaNoticia::class);
    }
}
