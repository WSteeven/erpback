<?php

namespace App\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\FormaPago
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|FormaPago acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FormaPago filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FormaPago ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FormaPago newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FormaPago newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FormaPago query()
 * @method static \Illuminate\Database\Eloquent\Builder|FormaPago setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FormaPago setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|FormaPago setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|FormaPago whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormaPago whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormaPago whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormaPago whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FormaPago extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'forma_pagos';
    protected $fillable = [
        'nombre'
    ];

    private static $whiteListFilter = [
        'id',
        'nombre',
    ];
}
