<?php

namespace App\Models\ComprasProveedores;

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
    use HasFactory, UppercaseValuesTrait, Filterable, Searchable, AuditableModel;

    // Tipos de cuentas
    const AHORRO = 'AHO';
    const CORRENTE = 'CTE';

    protected $table = 'cmp_cuentas_bancarias';
    protected $fillable = [
        'tipo_cuenta',
        'numero_cuenta',
        'banco_id',
        'beneficiario_id',
    ];

    private static array $whiteListFilter = ['*'];

    public function toSearchableArray()
    {
        return [
            'numero_cuenta' => $this['numero_cuenta'],
        ];
    }

    /**************
     * Relaciones
     **************/
    public function banco()
    {
        return $this->belongsTo(Banco::class);
    }

    public function beneficiario()
    {
        return $this->belongsTo(Beneficiario::class);
    }
}
