<?php

namespace App\Models\ComprasProveedores;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class CriterioCalificacion extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;


    protected $table = 'criterios_calificaciones';
    protected $fillable = [
        'nombre',
        'descripcion',
        'ponderacion_referencia',
        'departamento_id',
        'oferta_id',
    ];

    private static $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    
     public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }


    
    public function oferta()
    {
        return $this->belongsTo(OfertaProveedor::class);
    }
}
