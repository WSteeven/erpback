<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\ControlAvanceSubtarea
 *
 * @property int $id
 * @property string $fecha_hora
 * @property string $actividad
 * @property string|null $observacion
 * @property int $subtarea_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|ControlAvanceSubtarea newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ControlAvanceSubtarea newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ControlAvanceSubtarea query()
 * @method static \Illuminate\Database\Eloquent\Builder|ControlAvanceSubtarea whereActividad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlAvanceSubtarea whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlAvanceSubtarea whereFechaHora($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlAvanceSubtarea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlAvanceSubtarea whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlAvanceSubtarea whereSubtareaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlAvanceSubtarea whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ControlAvanceSubtarea extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;

    protected $table = "control_avance_subtareas";
    protected $fillable = [
        'fecha_hora',
        'actividad',
        'observacion',
        'subtarea_id',
    ];
}
