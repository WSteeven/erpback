<?php

namespace App\Models\Appenate;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class MaterialUtilizadoProgresiva extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;

    protected $table = 'appenate_materiales_utilizados_progresivas';
    protected $fillable = [
        'registro_id',
        'material',
        'cantidad',
    ];


    private static array $whiteListFilter = ['*'];

    public function registro(){
        return $this->belongsTo(RegistroProgresiva::class, 'registro_id','id');
    }

}
