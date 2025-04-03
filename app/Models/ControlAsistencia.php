<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\ControlAsistencia
 *
 * @property int $id
 * @property string $fecha_hora
 * @property string $foto
 * @property int $subtarea_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|ControlAsistencia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ControlAsistencia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ControlAsistencia query()
 * @method static \Illuminate\Database\Eloquent\Builder|ControlAsistencia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlAsistencia whereFechaHora($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlAsistencia whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlAsistencia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlAsistencia whereSubtareaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ControlAsistencia whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ControlAsistencia extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;

    protected $table = "control_asistencias";
    protected $fillable = [
        'codigo_tarea_jp',
        'codigo_tarea_cliente',
        'fecha_solicitud',
        'fecha_inicio',
        'fecha_finalizacion',
        'solicitante',
        'correo_solicitante',
        'detalle',
        'es_proyecto',
        'codigo_proyecto',
        'cliente_id',
    ];
}
