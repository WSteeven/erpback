<?php

namespace App\Models\Ventas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Ventas\PagoComision
 *
 * @property int $id
 * @property string|null $fecha_inicio
 * @property string|null $fecha_fin
 * @property int|null $vendedor_id
 * @property string|null $chargeback
 * @property string $valor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Ventas\Vendedor|null $vendedor
 * @method static \Illuminate\Database\Eloquent\Builder|PagoComision acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PagoComision filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PagoComision ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PagoComision newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PagoComision newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PagoComision query()
 * @method static \Illuminate\Database\Eloquent\Builder|PagoComision setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PagoComision setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PagoComision setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|PagoComision whereChargeback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PagoComision whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PagoComision whereFechaFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PagoComision whereFechaInicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PagoComision whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PagoComision whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PagoComision whereValor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PagoComision whereVendedorId($value)
 * @mixin \Eloquent
 */
class PagoComision extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'ventas_pagos_comisiones';
    protected $fillable = [
        'fecha_inicio',
        'fecha_fin',
        'vendedor_id',
        'chargeback',
        'valor',
        'pago'
    ];
    private static $whiteListFilter = [
        '*',
    ];
    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class, 'vendedor_id');
    }
    protected $casts = ['pago' => 'boolean'];
}
