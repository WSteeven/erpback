<?php

namespace App\Models\Ventas;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class BaseComision extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;


    protected $table = 'ventas_bases_comisiones';
    protected $fillable = [
        'modalidad_id',
        'presupuesto_ventas',
        'presupuesto_referidos',
        'bono_comision_semanal',
        'comisiones',
    ];

    protected $casts = [
        'comisiones' => 'array',
    ];
    private static array $whiteListFilter = ['*'];


    public function modalidad()
    {
        return $this->belongsTo(Modalidad::class);
    }

}
