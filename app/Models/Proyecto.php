<?php

namespace App\Models;

use App\Models\Tareas\Etapa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Proyecto
 *
 * @method static where(string $string, mixed $proyecto)
 * @property int $id
 * @property string $codigo_proyecto
 * @property string $nombre
 * @property string $nodo_interconexion
 * @property string $fecha_inicio
 * @property string $fecha_fin
 * @property bool $finalizado
 * @property int $coordinador_id
 * @property int|null $fiscalizador_id
 * @property int $canton_id
 * @property int $cliente_id
 * @property string|null $fecha_hora_finalizado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Canton|null $canton
 * @property-read \App\Models\Cliente|null $cliente
 * @property-read \App\Models\Empleado|null $coordinador
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Etapa> $etapas
 * @property-read int|null $etapas_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Etapa> $tareas
 * @property-read int|null $tareas_count
 * @method static \Illuminate\Database\Eloquent\Builder|Proyecto acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Proyecto filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Proyecto ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Proyecto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Proyecto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Proyecto porCoordinador()
 * @method static \Illuminate\Database\Eloquent\Builder|Proyecto query()
 * @method static \Illuminate\Database\Eloquent\Builder|Proyecto setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Proyecto setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Proyecto setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Proyecto whereCantonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proyecto whereClienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proyecto whereCodigoProyecto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proyecto whereCoordinadorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proyecto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proyecto whereFechaFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proyecto whereFechaHoraFinalizado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proyecto whereFechaInicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proyecto whereFinalizado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proyecto whereFiscalizadorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proyecto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proyecto whereNodoInterconexion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proyecto whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proyecto whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Proyecto extends Model implements Auditable
{
    use HasFactory, Filterable, UppercaseValuesTrait, AuditableModel;
    protected $table = "proyectos";

    protected $fillable = [
        'codigo_proyecto',
        'nombre',
        'cliente_id',
        'canton_id',
        'coordinador_id',
        'fiscalizador_id',
        'fecha_inicio',
        'fecha_fin',
        'fecha_hora_finalizado',
        'finalizado',
    ];

    protected $casts = [
        'finalizado' => 'boolean',
    ];

    private static $whiteListFilter = [
        '*',
        'etapas.responsable_id',
    ];

    // Relacion uno a muchos (inversa)
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relacion uno a muchos (inversa)
    public function coordinador()
    {
        return $this->belongsTo(Empleado::class, 'coordinador_id', 'id');
    }

    // Relacion uno a muchos (inversa)
    public function canton()
    {
        return $this->belongsTo(Canton::class);
    }
    /**
     * Relación uno a muchos.
     * Un proyecto tiene varias etapas
     */
    public function etapas()
    {
        return $this->hasMany(Etapa::class);
    }

    public function tareas()
    {
        return $this->hasMany(Etapa::class);
    }

    /*********
     * Scopes
     *********/
    public function scopePorCoordinador($query)
    {
        return $query->where('coordinador_id', Auth::user()->empleado->id);
    }
}
