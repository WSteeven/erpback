<?php

namespace App\Models\Administracion;

use App\Models\RecursosHumanos\Banco;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class CuentaBancaria extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable, Searchable;

    protected $table = 'adm_cuentas_bancarias';
    protected $fillable = [
        'es_principal',
        'banco_id',
        'tipo_cuenta',
        'numero_cuenta',
        'observacion',
    ];

    protected $casts = [
        'es_principal' => 'boolean',
    ];

    const CORRIENTE = 'CTE';
    const AHORROS = 'AHO';
    const PLAZO_FIJO = 'PLA';
    const INVERSION = 'INV';

    private static array $whiteListFilter = ['*'];

    public function banco()
    {
        return $this->belongsTo(Banco::class, 'banco_id');
    }

}
