<?php

namespace App\Models\Medico;

use App\Models\Archivo;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Medico\EsquemaVacuna
 *
 * @property int $id
 * @property int $dosis_aplicadas
 * @property string|null $observacion
 * @property int $paciente_id
 * @property int $tipo_vacuna_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $fecha
 * @property string|null $lote
 * @property string $responsable_vacunacion
 * @property string $establecimiento_salud
 * @property int $es_dosis_unica
 * @property string|null $fecha_caducidad
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Archivo> $archivos
 * @property-read int|null $archivos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Medico\TipoVacuna|null $tipoVacuna
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaVacuna acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaVacuna filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaVacuna ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaVacuna newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaVacuna newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaVacuna query()
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaVacuna setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaVacuna setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaVacuna setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaVacuna whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaVacuna whereDosisAplicadas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaVacuna whereEsDosisUnica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaVacuna whereEstablecimientoSalud($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaVacuna whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaVacuna whereFechaCaducidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaVacuna whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaVacuna whereLote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaVacuna whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaVacuna wherePacienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaVacuna whereResponsableVacunacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaVacuna whereTipoVacunaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EsquemaVacuna whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EsquemaVacuna extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_esquemas_vacunas';
    protected $fillable = [
        'dosis_aplicadas',
        'observacion',
        'fecha',
        'lote',
        'responsable_vacunacion',
        'establecimiento_salud',
        'es_dosis_unica',
        'fecha_caducidad',
        'paciente_id',
        'tipo_vacuna_id',
    ];

    private static $whiteListFilter = ['*'];

    // Relaciones
    /* public function registroEmpleadoExamen()
    {
        return $this->hasOne(RegistroEmpleadoExamen::class, 'id', 'registro_examen_id');
    } */

    public function tipoVacuna()
    {
        return $this->hasOne(TipoVacuna::class, 'id', 'tipo_vacuna_id');
    }

    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }
}
