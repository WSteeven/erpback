<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Medico\DiagnosticoFicha
 *
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Model|Eloquent $diagnosticable
 * @property-read DiagnosticoCitaMedica|null $diagnostico
 * @method static Builder|DiagnosticoFicha acceptRequest(?array $request = null)
 * @method static Builder|DiagnosticoFicha filter(?array $request = null)
 * @method static Builder|DiagnosticoFicha ignoreRequest(?array $request = null)
 * @method static Builder|DiagnosticoFicha newModelQuery()
 * @method static Builder|DiagnosticoFicha newQuery()
 * @method static Builder|DiagnosticoFicha query()
 * @method static Builder|DiagnosticoFicha setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|DiagnosticoFicha setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|DiagnosticoFicha setLoadInjectedDetection($load_default_detection)
 * @mixin Eloquent
 */
class DiagnosticoFicha extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;
    protected $table = 'med_diagnostico_fichas';
    protected $fillable = [
        'diagnostico_id',
        'tipo', //presuntivo o definitivo
        'diagnosticable_id',
        'diagnosticable_type',
    ];

    const PRESUNTIVO = 'PRESUNTIVO';
    const DEFINITIVO = 'DEFINITIVO';

    public function diagnostico()
    {
        return $this->belongsTo(DiagnosticoCitaMedica::class);
    }
    public function diagnosticable()
    {
        return $this->morphTo();
    }
}
