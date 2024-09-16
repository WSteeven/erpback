<?php

namespace App\Models\Ventas;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Ventas\CortePagoComision
 *
 * @property int $id
 * @property string $nombre
 * @property string $fecha_inicio
 * @property string $fecha_fin
 * @property string $estado
 * @property string|null $causa_anulacion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ventas\DetallePagoComision> $detalles
 * @property-read int|null $detalles_count
 * @method static \Illuminate\Database\Eloquent\Builder|CortePagoComision acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CortePagoComision filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CortePagoComision ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CortePagoComision newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CortePagoComision newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CortePagoComision query()
 * @method static \Illuminate\Database\Eloquent\Builder|CortePagoComision setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CortePagoComision setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CortePagoComision setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|CortePagoComision whereCausaAnulacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CortePagoComision whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CortePagoComision whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CortePagoComision whereFechaFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CortePagoComision whereFechaInicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CortePagoComision whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CortePagoComision whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CortePagoComision whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CortePagoComision extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait, Filterable;

    protected $table = 'ventas_cortes_pagos_comisiones';
    protected $fillable = [
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'causa_anulacion',
    ];

    const PENDIENTE = 'PENDIENTE';
    const COMPLETA = 'COMPLETA';
    const ANULADA = 'ANULADA';

    private static $whiteListFilter = [
        '*',
    ];

    public function detalles()
    {
        return $this->hasMany(DetallePagoComision::class, 'corte_id');
    }
}
