<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;

/**
 * App\Models\RutaTarea
 *
 * @property int $id
 * @property string $ruta
 * @property int $cliente_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool $activo
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Cliente|null $cliente
 * @method static \Illuminate\Database\Eloquent\Builder|RutaTarea acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RutaTarea filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RutaTarea ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RutaTarea newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RutaTarea newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RutaTarea query()
 * @method static \Illuminate\Database\Eloquent\Builder|RutaTarea setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RutaTarea setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RutaTarea setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|RutaTarea whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RutaTarea whereClienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RutaTarea whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RutaTarea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RutaTarea whereRuta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RutaTarea whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RutaTarea extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable, UppercaseValuesTrait;

    protected $table = "rutas_tareas";
    protected $fillable = [
        'ruta',
        'activo',
        'cliente_id',
    ];

    private static $whiteListFilter = [
        '*',
    ];

    protected $casts = ['activo' => 'boolean'];

    // Relacion uno a muchos (inversa)
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
