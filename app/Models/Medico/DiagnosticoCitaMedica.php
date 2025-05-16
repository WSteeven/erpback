<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Medico\DiagnosticoCitaMedica
 *
 * @property int $id
 * @property string $recomendacion
 * @property int $cie_id
 * @property int $consulta_medica_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Medico\Cie|null $cie
 * @property-read \App\Models\Medico\CitaMedica|null $citaMedica
 * @method static \Illuminate\Database\Eloquent\Builder|DiagnosticoCitaMedica acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DiagnosticoCitaMedica filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DiagnosticoCitaMedica ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DiagnosticoCitaMedica newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DiagnosticoCitaMedica newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DiagnosticoCitaMedica query()
 * @method static \Illuminate\Database\Eloquent\Builder|DiagnosticoCitaMedica setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DiagnosticoCitaMedica setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DiagnosticoCitaMedica setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|DiagnosticoCitaMedica whereCieId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiagnosticoCitaMedica whereConsultaMedicaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiagnosticoCitaMedica whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiagnosticoCitaMedica whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiagnosticoCitaMedica whereRecomendacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiagnosticoCitaMedica whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DiagnosticoCitaMedica extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_diagnosticos_cita_medica';
    protected $fillable = [
        'recomendacion',
        'cie_id',
        'consulta_medica_id',
        // 'cita_medica_id',
        // 'registro_empleado_examen_id',
    ];

    private static $whiteListFilter = ['*'];

    public function cie()
    {
        return $this->belongsTo(Cie::class); //, 'cie_id');
    }

    public function citaMedica()
    {
        return $this->belongsTo(CitaMedica::class);
    }
}
