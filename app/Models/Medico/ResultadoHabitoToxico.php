<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Medico\ResultadoHabitoToxico
 *
 * @property int $id
 * @property string $tiempo_consumo_meses
 * @property int|null $tiempo_abstinencia_meses
 * @property string $cantidad
 * @property bool $ex_consumidor
 * @property int $habito_toxicable_id
 * @property string $habito_toxicable_type
 * @property int $tipo_habito_toxico_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Model|\Eloquent $habitable
 * @property-read \App\Models\Medico\TipoHabitoToxico|null $tipoHabitoToxico
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoHabitoToxico newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoHabitoToxico newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoHabitoToxico query()
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoHabitoToxico whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoHabitoToxico whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoHabitoToxico whereExConsumidor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoHabitoToxico whereHabitoToxicableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoHabitoToxico whereHabitoToxicableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoHabitoToxico whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoHabitoToxico whereTiempoAbstinenciaMeses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoHabitoToxico whereTiempoConsumoMeses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoHabitoToxico whereTipoHabitoToxicoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResultadoHabitoToxico whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ResultadoHabitoToxico extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_resultados_habitos_toxicos';
    protected $fillable = [
        'tipo_habito_toxico_id',
        'tiempo_consumo_meses',
        'cantidad',
        'ex_consumidor',
        'tiempo_abstinencia_meses',
        'habito_toxicable_id',
        'habito_toxicable_type',
    ];

    protected $casts = [
        'ex_consumidor' => 'boolean',
    ];

    public function tipoHabitoToxico()
    {
        return $this->hasOne(TipoHabitoToxico::class, 'id', 'tipo_habito_toxico_id');
    }

    // Relación polimorfica
    public function habitable()
    {
        return $this->morphTo();
    }
}
