<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Medico\DiagnosticoFicha
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Model|\Eloquent $diagnosticable
 * @property-read \App\Models\Medico\DiagnosticoCitaMedica|null $diagnostico
 * @method static \Illuminate\Database\Eloquent\Builder|DiagnosticoFicha acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DiagnosticoFicha filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DiagnosticoFicha ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DiagnosticoFicha newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DiagnosticoFicha newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DiagnosticoFicha query()
 * @method static \Illuminate\Database\Eloquent\Builder|DiagnosticoFicha setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DiagnosticoFicha setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DiagnosticoFicha setLoadInjectedDetection($load_default_detection)
 * @mixin \Eloquent
 */
class DiagnosticoFicha extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;
    protected $table = 'med_diagnosticos_fichas';
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
