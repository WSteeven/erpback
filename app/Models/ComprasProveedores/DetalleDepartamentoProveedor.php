<?php

namespace App\Models\ComprasProveedores;

use App\Models\Archivo;
use App\Models\Departamento;
use App\Models\Empleado;
use App\Models\Proveedor;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\ComprasProveedores\DetalleDepartamentoProveedor
 *
 * @property int $id
 * @property int $departamento_id
 * @property int $proveedor_id
 * @property int|null $empleado_id
 * @property float|null $calificacion
 * @property string|null $fecha_calificacion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Archivo> $archivos
 * @property-read int|null $archivos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ComprasProveedores\CriterioCalificacion> $calificaciones_criterios
 * @property-read int|null $calificaciones_criterios_count
 * @property-read Departamento $departamento
 * @property-read Empleado|null $empleado
 * @property-read Proveedor $proveedor
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDepartamentoProveedor acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDepartamentoProveedor filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDepartamentoProveedor ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDepartamentoProveedor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDepartamentoProveedor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDepartamentoProveedor query()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDepartamentoProveedor setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDepartamentoProveedor setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDepartamentoProveedor setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDepartamentoProveedor whereCalificacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDepartamentoProveedor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDepartamentoProveedor whereDepartamentoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDepartamentoProveedor whereEmpleadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDepartamentoProveedor whereFechaCalificacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDepartamentoProveedor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDepartamentoProveedor whereProveedorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleDepartamentoProveedor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DetalleDepartamentoProveedor extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    use Filterable;

    protected $table = 'detalle_departamento_proveedor';
    protected $fillable = [
        'departamento_id',
        'proveedor_id',
        'empleado_id',
        'calificacion',
        'fecha_calificacion',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static $whiteListFilter = ['departamento_id', 'proveedor_id', 'empleado_id'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    public function departamento(){
        return $this->belongsTo(Departamento::class);
    }

    public function proveedor(){
        return $this->belongsTo(Proveedor::class);
    }
    /**
     * Relacion uno a muchos (inversa).
     * Una o varias calificaciones pertenece a un empleado
     */
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id', 'id');
    }

    public function calificaciones_criterios(){ 
        return $this->belongsToMany(CriterioCalificacion::class, 'calificacion_departamento_proveedor', 'detalle_departamento_id', 'criterio_calificacion_id')
        ->withPivot('comentario', 'peso', 'puntaje', 'calificacion')->withTimestamps();
    }

    /**
     * Relacion polimorfica con Archivos uno a muchos.
     *
     */
    public function archivos(){
        return $this->morphMany(Archivo::class, 'archivable');
    }


    /**
     * ______________________________________________________________________________________
     * FUNCIONES
     * ______________________________________________________________________________________
     */
}
