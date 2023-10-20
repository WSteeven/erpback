<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class PreingresoMaterial extends Model implements Auditable
{
    use HasFactory;
    use Filterable;
    use UppercaseValuesTrait;
    use AuditableModel;
    protected $table = 'preingresos_materiales';
    protected $fillable =  [
        'observacion',
        'cuadrilla',
        'num_guia',
        'courier',
        'fecha',
        'tarea_id',
        'cliente_id',
        'autorizador_id',
        'responsable_id',
        'coordinador_id',
        'autorizacion_id',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
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
     * Relación uno a uno (inversa).
     * Un preingreso pertenece a una o ninguna tarea
     */
    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }
    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios preingresos pertenecen a un cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
    }
    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios preingresos son autorizados por una persona
     */
    public function autorizador()
    {
        return $this->belongsTo(Empleado::class, 'autorizador_id', 'id');
    }
    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios preingresos tienen un responsable
     */
    public function responsable()
    {
        return $this->belongsTo(Empleado::class, 'responsable_id', 'id');
    }
    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios preingresos tienen un coordinador
     */
    public function coordinador()
    {
        return $this->belongsTo(Empleado::class, 'coordinador_id', 'id');
    }

    /**
     * Relación uno a uno(inversa).
     * Uno o varios preingresos solo pueden tener una autorización.
     */
    public function autorizacion()
    {
        return $this->belongsTo(Autorizacion::class);
    }

    public function detalles()
    {
        return $this->belongsToMany(DetalleProducto::class, 'item_detalle_preingreso_material', 'preingreso_id', 'detalle_id')
            ->withPivot('descripcion', 'cantidad', 'serial', 'punta_inicial', 'punta_final', 'unidad_medida_id', 'fotografia')->withTimestamps();
    }

    /**
     * ______________________________________________________________________________________
     * FUNCIONES
     * ______________________________________________________________________________________
     */
    public static function almacenarDetalles($preingreso, $listado)
    {
        try {
            Log::channel('testing')->info('Log', ['Preingreso:', $preingreso]);
            Log::channel('testing')->info('Log', ['Listado de detalles a almacenar:', $listado]);

        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
