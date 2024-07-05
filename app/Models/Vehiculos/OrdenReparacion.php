<?php

namespace App\Models\Vehiculos;

use App\Models\Archivo;
use App\Models\Autorizacion;
use App\Models\Empleado;
use App\Models\Notificacion;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class OrdenReparacion extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;

    protected $table = 'veh_ordenes_reparaciones';
    protected $fillable = [
        'solicitante_id',
        'autorizador_id',
        'autorizacion_id',
        'vehiculo_id',
        'servicios',
        'observacion',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];


    private static $whiteListFilter = ['*'];



    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    public function solicitante()
    {
        return $this->belongsTo(Empleado::class, 'solicitante_id', 'id');
    }
    public function autorizador()
    {
        return $this->belongsTo(Empleado::class, 'autorizador_id', 'id');
    }
    public function autorizacion()
    {
        return $this->belongsTo(Autorizacion::class);
    }

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }

    /**
     * Relación para obtener la ultima notificacion de un modelo dado.
     */
    public function latestNotificacion()
    {
        return $this->morphOne(Notificacion::class, 'notificable')->latestOfMany();
    }

    /**
     * Relacion polimorfica a una notificacion.
     * Una orden de compra puede tener una o varias notificaciones.
     */
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }

    /**
     * Relacion polimorfica con Archivos uno a muchos.
     *
     */
    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }

    public function kmRealizado($vehiculo_id, $fecha)
    {
        $bitacora = BitacoraVehicular::where('vehiculo_id', $vehiculo_id)->where('created_at', '>=', $fecha)->first();
        if ($bitacora)
            return $bitacora?->km_inicial;
        else {
            $bitacora = BitacoraVehicular::where('vehiculo_id', $vehiculo_id)->where('created_at', '<=', $fecha)->orderBy('created_at', 'desc')->first();
            return $bitacora?->km_inicial;
        }
    }
}
