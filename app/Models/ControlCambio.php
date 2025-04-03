<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\ControlCambio
 *
 * @property int $id
 * @property string $numero_elemento
 * @property string $cambios
 * @property string|null $georeferencia_x
 * @property string|null $georeferencia_y
 * @property string|null $aprobado_por
 * @property int $subtarea_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|ControlCambio newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ControlCambio newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ControlCambio query()
 * @method static \Illuminate\Database\Eloquent\Builder|ControlCambio whereAprobadoPor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlCambio whereCambios($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlCambio whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlCambio whereGeoreferenciaX($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlCambio whereGeoreferenciaY($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlCambio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlCambio whereNumeroElemento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlCambio whereSubtareaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlCambio whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ControlCambio extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
}
