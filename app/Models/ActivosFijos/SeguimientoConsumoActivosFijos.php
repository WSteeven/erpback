<?php

namespace App\Models\ActivosFijos;

use App\Models\Archivo;
use App\Models\Canton;
use App\Models\Cliente;
use App\Models\DetalleProducto;
use App\Models\Empleado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class SeguimientoConsumoActivosFijos extends Model implements Auditable
{
    use HasFactory, Filterable, AuditableModel;

    protected $table = 'af_seguimientos_consumo_activos_fijos';
    protected $fillable = [
        'stock_actual',
        'cantidad_utilizada',
        'empleado_id',
        'detalle_producto_id',
        'cliente_id',
        'canton_id',
        'motivo_consumo_activo_fijo_id',
        'observacion',
        'se_reporto_sicosep'
    ];

    private static $whiteListFilter = ['*'];

    protected $casts = ['se_reporto_sicosep' => 'boolean'];

    /***********************
     * Constantes archivos
     ***********************/
    const JUSTIFICATIVO_USO = 'JUSTIFICATIVO USO';

    /*************
     * Relaciones
     *************/
    public function motivoConsumoActivoFijo()
    {
        return $this->belongsTo(MotivoConsumoActivoFijo::class);
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function detalleProducto()
    {
        return $this->belongsTo(DetalleProducto::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function canton()
    {
        return $this->belongsTo(Canton::class);
    }

    public function scopeResponsable($query)
    {
        return $query->where('empleado_id', Auth::user()->empleado->id);
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
