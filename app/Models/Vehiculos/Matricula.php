<?php

namespace App\Models\Vehiculos;

use App\Models\Notificacion;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Matricula extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, Filterable, UppercaseValuesTrait;


    protected $table = 'veh_matriculas';
    protected $fillable = [
        'vehiculo_id',
        'fecha_matricula',
        'proxima_matricula',
        'valor_estimado_pagar',
        'fecha_pago',
        'matriculador',
        'matriculado',
        'observacion',
        'monto',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'matriculado' => 'boolean',
    ];

    private static $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    /**
     * Relación uno a muchos (inversa).
     * Una o varias multas pertenecen a un Conductor.
     */
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }

    /**
     * Relacion polimorfica a una notificacion.
     * Una matricula puede tener una o varias notificaciones.
     */
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }

    /**
     * Relación para obtener la ultima notificacion de un modelo dado.
     */
    public function latestNotificacion()
    {
        return $this->morphOne(Notificacion::class, 'notificable')->latestOfMany();
    }

    /**
     * ______________________________________________________________________________________
     * FUNCIONES
     * ______________________________________________________________________________________
     */
    public static function crearMatricula($vehiculo_id, $fecha_matricula, $proxima_matricula)
    {
        try {
            DB::beginTransaction();
            $matricula = Matricula::create([
                'vehiculo_id' => $vehiculo_id,
                'fecha_matricula' => $fecha_matricula,
                'proxima_matricula' => $proxima_matricula,
            ]);
            DB::commit();
            return $matricula;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception($th->getMessage() . '. [LINE CODE ERROR]: ' . $th->getLine(), $th->getCode());
        }
    }
}
