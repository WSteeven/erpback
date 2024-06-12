<?php

namespace App\Models\Vehiculos;

use App\Models\Archivo;
use App\Models\Empleado;
use App\Models\Modelo;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

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

    //Transmisiones
    // const MANUAL='MANUAL';
    // const AUTOMATICA='AUTOMATICA';
    // const SECUENCIAL='SECUENCIAL';
    // const CVT='CONITNUA VARIABLE (CVT)';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'aire_acondicionado' => 'boolean',
        'tiene_gravamen' => 'boolean',
        'tiene_rastreo' => 'boolean',
    ];

    private static $whiteListFilter = [
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
     * Un vehiculo tiene solo un tipo de vehiculo a la vez.
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
     * Un vehículo tiene una o varias matriculas a lo largo del tiempo.
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
        if ($metodo == 'show') {
            $aplicar_desde = $items->max('aplicar_desde');
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
            return [
                $aplicar_desde,
                $estado,
                $listadoServicios,
            ];
        } else {
            $aplicar_desde = $items->max('aplicar_desde');
            $estado = $items->where('estado', 1)->count() > $items->where('estado', 0)->count();
            return [
                $aplicar_desde,
                $estado,
                $listadoServicios,
            ];
        }
    }
}
