<?php

namespace App\Models\Vehiculos;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;


class Tanqueo extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait;
    use Filterable;
    use AuditableModel;

    protected $table = 'veh_tanqueos';
    protected $fillable = [
        'vehiculo_id',
        'solicitante_id',
        'fecha_hora',
        'km_tanqueo',
        'monto',
        'combustible_id',
        'imagen_comprobante',
        'imagen_tablero'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static $whiteListFilter = [
        '*',
    ];

    /** Se definen las constantes del tipo de reporte en la seccion de reporte de combustibles */
    const TIPO_RPT_COMBUSTIBLE = 'COMBUSTIBLE';
    const TIPO_RPT_VEHICULO = 'VEHICULO';

    /**
     * Relación uno a muchos (inversa).
     */
    public function combustible()
    {
        return $this->belongsTo(Combustible::class);
    }
    /**
     * Relación uno a muchos
     */
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }
    public function solicitante()
    {
        return $this->belongsTo(Empleado::class, 'solicitante_id', 'id');
    }
}
