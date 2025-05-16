<?php

namespace App\Models\Vehiculos;

use App\Models\Archivo;
use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Vehiculos\RegistroIncidente
 *
 * @property int $id
 * @property int|null $vehiculo_id
 * @property int $persona_reporta_id
 * @property int $persona_registra_id
 * @property string $fecha
 * @property string $descripcion
 * @property string $tipo
 * @property string $gravedad
 * @property bool $aplica_seguro
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Archivo> $archivos
 * @property-read int|null $archivos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $personaRegistra
 * @property-read Empleado|null $personaReporta
 * @property-read \App\Models\Vehiculos\Vehiculo|null $vehiculo
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroIncidente acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroIncidente filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroIncidente ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroIncidente newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroIncidente newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroIncidente query()
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroIncidente setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroIncidente setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroIncidente setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroIncidente whereAplicaSeguro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroIncidente whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroIncidente whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroIncidente whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroIncidente whereGravedad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroIncidente whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroIncidente wherePersonaRegistraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroIncidente wherePersonaReportaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroIncidente whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroIncidente whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegistroIncidente whereVehiculoId($value)
 * @mixin \Eloquent
 */
class RegistroIncidente extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait, Filterable;

    protected $table = 'veh_registros_incidentes';
    protected $fillable = [
        'vehiculo_id',
        'fecha',
        'descripcion',
        'tipo',
        'gravedad',
        'persona_reporta_id',
        'persona_registra_id',
        'aplica_seguro',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'aplica_seguro' => 'boolean',
    ];

    private static $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }
    public function personaReporta()
    {
        return $this->belongsTo(Empleado::class, 'persona_reporta_id', 'id');
    }
    public function personaRegistra()
    {
        return $this->belongsTo(Empleado::class, 'persona_registra_id', 'id');
    }

    /**
     * Relacion polimorfica con Archivos uno a muchos.
     *
     */
    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }
}
