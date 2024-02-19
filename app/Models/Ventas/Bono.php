<?php

namespace App\Models\Ventas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;


class Bono extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'ventas_bonos';
    protected $fillable = ['cant_ventas', 'valor'];
    private static $whiteListFilter = [
        '*',
    ];

    /**
     * Relacion polimorfica a un bono de cumplimiento.
     * Un bono puede tener uno o varios registos en un bono Mensual de cumplimiento.
     */
    public function bonosCumplimiento()
    {
        return $this->morphMany(BonoMensualCumplimiento::class, 'bonificable');
    }
}
