<?php

namespace App\Models\ComprasProveedores;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class GeneradorCash extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'cmp_generador_cash';
    protected $fillable = [
        'titulo',
        'creador_id',
    ];

    private static array $whiteListFilter = ['*'];

    /**************
     * Relaciones
     **************/
    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    public function creador()
    {
        return $this->belongsTo(Empleado::class, 'creador_id');
    }
}
