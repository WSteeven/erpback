<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\EmpleadoSubtarea
 *
 * @property int $id
 * @property bool $es_responsable
 * @property int $empleado_id
 * @property int $subtarea_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|EmpleadoSubtarea newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmpleadoSubtarea newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmpleadoSubtarea query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmpleadoSubtarea whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmpleadoSubtarea whereEmpleadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmpleadoSubtarea whereEsResponsable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmpleadoSubtarea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmpleadoSubtarea whereSubtareaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmpleadoSubtarea whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EmpleadoSubtarea extends Model implements Auditable
{
    use HasFactory, AuditableModel;
    protected $table = 'empleado_subtarea';
    protected $fillable = [
        'es_responsable',
        'empleado_id',
        'subtarea_id',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'es_responsable' => 'boolean',
    ];
}
