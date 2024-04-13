<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class CategoriaExamenFisico extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;


    protected $table = 'med_categorias_examenes_fisicos';
    protected $fillable = [
        'nombre',
        'region'
    ];
    private static $whiteListFilter = ['*'];
}
