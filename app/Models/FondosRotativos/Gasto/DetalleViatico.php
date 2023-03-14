<?php

namespace App\Models\FondosRotativos\Gasto;

use App\Models\FondosRotativos\Usuario\Estatus;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class DetalleViatico extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'detalle_viatico';
    protected $primaryKey = 'id';

    protected $fillable = [
        'descripcion',
        'autorizacion',
        'id_estatus',
    ];

    private static $whiteListFilter = [
        'descripcion',
    ];
    public function estatus()
    {
        return $this->hasOne(Estatus::class, 'id','id_estatus');
    }
    public function gastos(){
        return $this->hasMany(Gasto::class,'detalle','id');
    }
}
