<?php

namespace App\Models\ComprasProveedores;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class Pago extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, Searchable, AuditableModel;

    // Tipo de transacción
    const PAGOS = 'PA';
    const COBROS = 'CO';

    // Formas de pago
    const CREDITO_A_CUENTA = 'CTA';
    const EMISION_CHEQUE = 'CHQ';
    const ENTREGA_EN_EFECTIVO = 'EFE';

    protected $table = 'cmp_pagos';
    protected $fillable = [
        'tipo',
        'num_cuenta_empresa',
        'num_secuencial',
        'num_comprobante',
        'moneda',
        'valor',
        'forma_pago',
        'referencia',
        'referencia_adicional',
        'cuenta_banco_id',
        'beneficiario_id',
        'generador_cash_id',
    ];

    private static array $whiteListFilter = ['*'];

    public function toSearchableArray()
    {
        return [
            'num_comprobante' => $this['num_comprobante'],
        ];
    }

    /**************
     * Relaciones
     **************/
    public function cuentaBanco()
    {
        return $this->belongsTo(CuentaBancaria::class);
    }

    public function beneficiario()
    {
        return $this->belongsTo(Beneficiario::class);
    }
}
