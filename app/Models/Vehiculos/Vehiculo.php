<?php

namespace App\Models\Vehiculos;

use App\Models\Archivo;
use App\Models\Empleado;
use App\Models\Modelo;
use App\Traits\UppercaseValuesTrait;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Vehiculos\Vehiculo
 *
 * @method static find(mixed $vehiculo_id)
 * @method static whereNot(string $string, $null)
 * @method static where(string $string, string $string1, $null)
 * @property int $id
 * @property string $tipo
 * @property string $placa
 * @property string $num_chasis
 * @property string $num_motor
 * @property int|null $tipo_vehiculo_id
 * @property int $anio_fabricacion
 * @property int $cilindraje
 * @property int $rendimiento
 * @property int|null $custodio_id
 * @property int|null $seguro_id
 * @property string $traccion
 * @property string|null $propietario
 * @property string|null $identificacion_conductor_externo
 * @property string|null $conductor_externo
 * @property string $color
 * @property bool $aire_acondicionado
 * @property bool $tiene_gravamen
 * @property bool $tiene_rastreo
 * @property string|null $prendador
 * @property float $capacidad_tanque
 * @property int $modelo_id
 * @property int $combustible_id
 * @property int $estado
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Archivo> $archivos
 * @property-read int|null $archivos_count
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Collection<int, Empleado> $bitacoras
 * @property-read int|null $bitacoras_count
 * @property-read Combustible|null $combustible
 * @property-read Empleado|null $custodio
 * @property-read Collection<int, PlanMantenimiento> $itemsMantenimiento
 * @property-read int|null $items_mantenimiento_count
 * @property-read Collection<int, Matricula> $matriculas
 * @property-read int|null $matriculas_count
 * @property-read Modelo|null $modelo
 * @property-read SeguroVehicular|null $seguro
 * @property-read TipoVehiculo|null $tipoVehiculo
 * @method static Builder|Vehiculo acceptRequest(?array $request = null)
 * @method static Builder|Vehiculo filter(?array $request = null)
 * @method static Builder|Vehiculo ignoreRequest(?array $request = null)
 * @method static Builder|Vehiculo newModelQuery()
 * @method static Builder|Vehiculo newQuery()
 * @method static Builder|Vehiculo query()
 * @method static Builder|Vehiculo setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Vehiculo setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Vehiculo setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Vehiculo whereAireAcondicionado($value)
 * @method static Builder|Vehiculo whereAnioFabricacion($value)
 * @method static Builder|Vehiculo whereCapacidadTanque($value)
 * @method static Builder|Vehiculo whereCilindraje($value)
 * @method static Builder|Vehiculo whereColor($value)
 * @method static Builder|Vehiculo whereCombustibleId($value)
 * @method static Builder|Vehiculo whereConductorExterno($value)
 * @method static Builder|Vehiculo whereCreatedAt($value)
 * @method static Builder|Vehiculo whereCustodioId($value)
 * @method static Builder|Vehiculo whereEstado($value)
 * @method static Builder|Vehiculo whereId($value)
 * @method static Builder|Vehiculo whereIdentificacionConductorExterno($value)
 * @method static Builder|Vehiculo whereModeloId($value)
 * @method static Builder|Vehiculo whereNumChasis($value)
 * @method static Builder|Vehiculo whereNumMotor($value)
 * @method static Builder|Vehiculo wherePlaca($value)
 * @method static Builder|Vehiculo wherePrendador($value)
 * @method static Builder|Vehiculo wherePropietario($value)
 * @method static Builder|Vehiculo whereRendimiento($value)
 * @method static Builder|Vehiculo whereSeguroId($value)
 * @method static Builder|Vehiculo whereTieneGravamen($value)
 * @method static Builder|Vehiculo whereTieneRastreo($value)
 * @method static Builder|Vehiculo whereTipo($value)
 * @method static Builder|Vehiculo whereTipoVehiculoId($value)
 * @method static Builder|Vehiculo whereTraccion($value)
 * @method static Builder|Vehiculo whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Vehiculo extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;

    protected $table = 'vehiculos';
    protected $fillable = [
        'placa',
        'num_chasis',
        'num_motor',
        'anio_fabricacion',
        'cilindraje',
        'rendimiento',
        'traccion',
        'aire_acondicionado',
        'capacidad_tanque',
        'modelo_id',
        'combustible_id',
        'tipo_vehiculo_id',
        'tiene_gravamen',
        'color',
        'prendador',
        'tipo',
        'tiene_rastreo',
        'propietario',
        'custodio_id',
        'seguro_id',
        'conductor_externo',
        'identificacion_conductor_externo',
    ];

    //Tracciones
    const SENCILLA_DELANTERA = '4X2 FWD';
    const SENCILLA_TRASERA = '4X2 RWD';
    const AWD = 'AWD';
    const FOUR_WD = '4WD';
    const TODOTERRENO = '4X4';
    const DOSXUNO = '2X1';
    const DOSXDOS = '2X2';

    //Tipos de vehiculos
    const PROPIO = 'PROPIO';
    const ALQUILADO = 'ALQUILADO';

    //Tipos para el historial de vehiculos
    const TODOS = 'TODOS';
    const MANTENIMIENTOS = 'MANTENIMIENTOS';
    const INCIDENTES = 'INCIDENTES';
    const CUSTODIOS = 'CUSTODIOS';



    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'aire_acondicionado' => 'boolean',
        'tiene_gravamen' => 'boolean',
        'tiene_rastreo' => 'boolean',
    ];

    private static array $whiteListFilter = [
        '*',
    ];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    /**
     * Relación uno a muchos (inversa).
     */
    public function combustible()
    {
        return $this->belongsTo(Combustible::class);
    }

    public function seguro()
    {
        return $this->belongsTo(SeguroVehicular::class);
    }

    /**
     * Realación uno a muchos (inversa).
     * Un vehículo tiene solo un tipo de vehículo a la vez.
     */
    public function tipoVehiculo()
    {
        return $this->belongsTo(TipoVehiculo::class);
    }

    /**
     * Relación uno a muchos
     */
    public function itemsMantenimiento()
    {
        return $this->hasMany(PlanMantenimiento::class);
    }

    /**
     * Relación uno a muchos (inversa).
     */
    public function modelo()
    {
        return $this->belongsTo(Modelo::class);
    }
    /**
     * Realación muchos a muchos.
     * Un vehículo tiene varias bitacoras
     */
    public function bitacoras()
    {
        return $this->belongsToMany(Empleado::class, 'veh_bitacoras_vehiculos', 'vehiculo_id', 'chofer_id')
            ->withPivot('fecha', 'hora_salida', 'hora_llegada', 'km_inicial', 'km_final', 'tanque_inicio', 'tanque_final', 'firmada')->withTimestamps();
    }

    /**
     * Relación uno a muchos.
     * Un vehículo tiene una o varias matrículas a lo largo del tiempo.
     */
    public function matriculas()
    {
        return $this->hasMany(Matricula::class);
    }

    /**
     * Relacion polimorfica con Archivos uno a muchos.
     *
     */
    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }

    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios pedidos tienen un responsable
     */
    public function custodio()
    {
        return $this->belongsTo(Empleado::class);
    }


    /**
     * ______________________________________________________________________________________
     * FUNCIONES
     * ______________________________________________________________________________________
     */
    public static function listadoItemsPlanMantenimiento($id, $metodo)
    {
        $items = Vehiculo::find($id)->itemsMantenimiento()->get();
        $listadoServicios = $items;
        $aplicar_desde = $items->max('aplicar_desde');
        if ($metodo == 'show') {
            $estado = $items->where('activo', true)->count() > $items->where('activo', false)->count();
            foreach ($items as $index => $item) {


                $servicio = Servicio::find($item->servicio_id);
                $listadoServicios[$index] = [
                    'id' => $servicio->id,
                    'nombre' => $servicio->nombre,
                    'tipo' => $servicio->tipo,
                    'intervalo' => $item->aplicar_cada,
                    'notificar_antes' => $item->notificar_antes,
                    'datos_adicionales' => $item->datos_adicionales,
                    'estado' => $item->activo,
                ];
            }
        } else {
            $estado = $items->where('estado', 1)->count() > $items->where('estado', 0)->count();
        }
        return [
            $aplicar_desde,
            $estado,
            $listadoServicios,
        ];
    }
}
