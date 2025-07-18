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
use OwenIt\Auditing\Models\Audit;

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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Model|Eloquent $habitable
 * @property-read TipoHabitoToxico|null $tipoHabitoToxico
 * @method static Builder|ResultadoHabitoToxico newModelQuery()
 * @method static Builder|ResultadoHabitoToxico newQuery()
 * @method static Builder|ResultadoHabitoToxico query()
 * @method static Builder|ResultadoHabitoToxico whereCantidad($value)
 * @method static Builder|ResultadoHabitoToxico whereCreatedAt($value)
 * @method static Builder|ResultadoHabitoToxico whereExConsumidor($value)
 * @method static Builder|ResultadoHabitoToxico whereHabitoToxicableId($value)
 * @method static Builder|ResultadoHabitoToxico whereHabitoToxicableType($value)
 * @method static Builder|ResultadoHabitoToxico whereId($value)
 * @method static Builder|ResultadoHabitoToxico whereTiempoAbstinenciaMeses($value)
 * @method static Builder|ResultadoHabitoToxico whereTiempoConsumoMeses($value)
 * @method static Builder|ResultadoHabitoToxico whereTipoHabitoToxicoId($value)
 * @method static Builder|ResultadoHabitoToxico whereUpdatedAt($value)
 * @mixin Eloquent
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
