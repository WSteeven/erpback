<?php

namespace App\Models\Medico;

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
 * App\Models\Medico\DiagnosticoCitaMedica
 *
 * @property int $id
 * @property string $recomendacion
 * @property int $cie_id
 * @property int $consulta_medica_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Cie|null $cie
 * @property-read CitaMedica|null $citaMedica
 * @method static Builder|DiagnosticoCitaMedica acceptRequest(?array $request = null)
 * @method static Builder|DiagnosticoCitaMedica filter(?array $request = null)
 * @method static Builder|DiagnosticoCitaMedica ignoreRequest(?array $request = null)
 * @method static Builder|DiagnosticoCitaMedica newModelQuery()
 * @method static Builder|DiagnosticoCitaMedica newQuery()
 * @method static Builder|DiagnosticoCitaMedica query()
 * @method static Builder|DiagnosticoCitaMedica setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|DiagnosticoCitaMedica setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|DiagnosticoCitaMedica setLoadInjectedDetection($load_default_detection)
 * @method static Builder|DiagnosticoCitaMedica whereCieId($value)
 * @method static Builder|DiagnosticoCitaMedica whereConsultaMedicaId($value)
 * @method static Builder|DiagnosticoCitaMedica whereCreatedAt($value)
 * @method static Builder|DiagnosticoCitaMedica whereId($value)
 * @method static Builder|DiagnosticoCitaMedica whereRecomendacion($value)
 * @method static Builder|DiagnosticoCitaMedica whereUpdatedAt($value)
 * @mixin Eloquent
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

    private static array $whiteListFilter = ['*'];

    public function cie()
    {
        return $this->belongsTo(Cie::class); //, 'cie_id');
    }

    public function citaMedica()
    {
        return $this->belongsTo(CitaMedica::class);
    }
}
