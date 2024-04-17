<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;


class ExamenRealizado extends Model  implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_examenes_realizados';
    protected $fillable = [
        'examen_id',
        'tiempo', //texto formato año-mes debe permitir solo el año o año y fecha  
        'resultado',
        'ficha_preocupacional_id',
    ];
    private static $whiteListFilter = ['*'];


    public function fichaPreocupacional(){
        return $this->belongsTo(fichaPreocupacional::class);
    }
    public function examen(){
        return $this->hasOne(ExamenOrganoReproductivo::class);
    }
}
