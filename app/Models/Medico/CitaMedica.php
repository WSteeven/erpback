<?php

namespace App\Models\Medico;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $tipo_cambio_cargo
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Medico\ConsultaMedica|null $consultaMedica
 * @property-read Empleado|null $paciente
 * @method static \Illuminate\Database\Eloquent\Builder|CitaMedica acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CitaMedica filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CitaMedica ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CitaMedica newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CitaMedica newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CitaMedica query()
 * @method static \Illuminate\Database\Eloquent\Builder|CitaMedica setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CitaMedica setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CitaMedica setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|CitaMedica whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CitaMedica whereEstadoCitaMedica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CitaMedica whereFechaHoraAccidente($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CitaMedica whereFechaHoraCancelado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CitaMedica whereFechaHoraCita($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CitaMedica whereFechaHoraRechazo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CitaMedica whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CitaMedica whereMotivoCancelacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CitaMedica whereMotivoRechazo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CitaMedica whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CitaMedica wherePacienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CitaMedica whereSintomas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CitaMedica whereTipoCambioCargo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CitaMedica whereTipoCitaMedica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CitaMedica whereUpdatedAt($value)
 * @mixin \Eloquent
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

    private static $whiteListFilter = ['*'];

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
}
