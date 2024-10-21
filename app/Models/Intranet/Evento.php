<?php

namespace App\Models\Intranet;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Intranet\Evento
 *
 * @property int $id
 * @property string $titulo
 * @property int $tipo_evento_id
 * @property int $anfitrion_id
 * @property string $descripcion
 * @property string $fecha_hora_inicio
 * @property string $fecha_hora_fin
 * @property bool $es_editable
 * @property bool $es_personalizado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Empleado|null $anfitrion
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Intranet\TipoEvento|null $tipoEvento
 * @method static \Illuminate\Database\Eloquent\Builder|Evento acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Evento filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Evento ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Evento newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Evento newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Evento query()
 * @method static \Illuminate\Database\Eloquent\Builder|Evento setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Evento setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Evento setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Evento whereAnfitrionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Evento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Evento whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Evento whereEsEditable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Evento whereEsPersonalizado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Evento whereFechaHoraFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Evento whereFechaHoraInicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Evento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Evento whereTipoEventoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Evento whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Evento whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Evento extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'intra_eventos';
    protected $fillable = [
        'titulo',
        'tipo_evento_id',
        'anfitrion_id',
        'descripcion',
        'fecha_hora_inicio',
        'fecha_hora_fin',
        'es_editable',
        'es_personalizado',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i',
        'updated_at' => 'datetime:Y-m-d h:i',
        'es_editable'=>'boolean',
        'es_personalizado'=>'boolean',
    ];


    private static array $whiteListFilter = [
        '*',
    ];

    public function tipoEvento(){
        return $this->belongsTo(TipoEvento::class);
    }

    public function anfitrion(){
        return $this->belongsTo(Empleado::class);
    }
}
