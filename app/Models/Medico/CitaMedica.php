<?php

namespace App\Models\Medico;

use App\Models\Empleado;
use App\Models\Notificacion;
use App\Traits\UppercaseValuesTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Medico\CitaMedica
 *
 * @property int $id
 * @property string $sintomas
 * @property string|null $observacion
 * @property string|null $fecha_hora_cita
 * @property string|null $fecha_hora_accidente
 * @property string $estado_cita_medica
 * @property string $tipo_cita_medica
 * @property string|null $motivo_rechazo
 * @property string|null $motivo_cancelacion
 * @property string|null $fecha_hora_rechazo
 * @property string|null $fecha_hora_cancelado
 * @property int $paciente_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $tipo_cambio_cargo
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read ConsultaMedica|null $consultaMedica
 * @property-read Empleado|null $paciente
 * @method static Builder|CitaMedica acceptRequest(?array $request = null)
 * @method static Builder|CitaMedica filter(?array $request = null)
 * @method static Builder|CitaMedica ignoreRequest(?array $request = null)
 * @method static Builder|CitaMedica newModelQuery()
 * @method static Builder|CitaMedica newQuery()
 * @method static Builder|CitaMedica query()
 * @method static Builder|CitaMedica setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|CitaMedica setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|CitaMedica setLoadInjectedDetection($load_default_detection)
 * @method static Builder|CitaMedica whereCreatedAt($value)
 * @method static Builder|CitaMedica whereEstadoCitaMedica($value)
 * @method static Builder|CitaMedica whereFechaHoraAccidente($value)
 * @method static Builder|CitaMedica whereFechaHoraCancelado($value)
 * @method static Builder|CitaMedica whereFechaHoraCita($value)
 * @method static Builder|CitaMedica whereFechaHoraRechazo($value)
 * @method static Builder|CitaMedica whereId($value)
 * @method static Builder|CitaMedica whereMotivoCancelacion($value)
 * @method static Builder|CitaMedica whereMotivoRechazo($value)
 * @method static Builder|CitaMedica whereObservacion($value)
 * @method static Builder|CitaMedica wherePacienteId($value)
 * @method static Builder|CitaMedica whereSintomas($value)
 * @method static Builder|CitaMedica whereTipoCambioCargo($value)
 * @method static Builder|CitaMedica whereTipoCitaMedica($value)
 * @method static Builder|CitaMedica whereUpdatedAt($value)
 * @mixin Eloquent
 */
class CitaMedica extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    const PENDIENTE = 'PENDIENTE';
    const AGENDADO = 'AGENDADO';
    const ATENDIDO = 'ATENDIDO';
    const CANCELADO = 'CANCELADO';
    const RECHAZADO = 'RECHAZADO';

    // Tipo de cita medica
    const ENFERMEDAD_COMUN = 'ENFERMEDAD COMUN';
    const ACCIDENTE_DE_TRABAJO = 'ACCIDENTE DE TRABAJO';

    protected $table = 'med_citas_medicas';
    protected $fillable = [
        'sintomas',
        'observacion',
        'fecha_hora_cita',
        'fecha_hora_accidente',
        'estado_cita_medica',
        'tipo_cita_medica',
        'motivo_rechazo',
        'motivo_cancelacion',
        'fecha_hora_rechazo',
        'fecha_hora_cancelado',
        'tipo_cambio_cargo',
        'certificado_alta',
        'observaciones_alta',
        'restricciones_alta',
        'paciente_id',
        'accidente_id',
    ];

    private static array $whiteListFilter = ['*'];

    /*************
     * Relaciones
     *************/
    /*public function estadoCitaMedica()
    {
        return $this->belongsToMany(EstadoCitaMedica::class, 'estado_cita_medica_id');
    }*/

    public function paciente()
    {
        return $this->belongsTo(Empleado::class, 'paciente_id', 'id');
    }

    public function consultaMedica()
    {
        return $this->hasOne(ConsultaMedica::class);
    }

    /**
     * Relacion polimorfica a una notificacion.
     * Una cita médica puede tener una o varias notificaciones.
     */
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }

}
