<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\SeguimientoMaterialSubtarea
 *
 * @property int $id
 * @property int $stock_actual
 * @property int $cantidad_utilizada
 * @property string|null $fecha
 * @property int|null $tarea_id
 * @property int $subtarea_id
 * @property int $empleado_id
 * @property int|null $grupo_id
 * @property int $detalle_producto_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $cliente_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Cliente|null $cliente
 * @property-read \App\Models\DetalleProducto|null $detalleProducto
 * @property-read \App\Models\Empleado|null $empleado
 * @property-read \App\Models\MaterialEmpleadoTarea|null $materialEmpleadoTarea
 * @property-read \App\Models\Subtarea|null $subtarea
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoMaterialSubtarea newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoMaterialSubtarea newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoMaterialSubtarea query()
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoMaterialSubtarea responsable()
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoMaterialSubtarea whereCantidadUtilizada($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoMaterialSubtarea whereClienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoMaterialSubtarea whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoMaterialSubtarea whereDetalleProductoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoMaterialSubtarea whereEmpleadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoMaterialSubtarea whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoMaterialSubtarea whereGrupoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoMaterialSubtarea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoMaterialSubtarea whereStockActual($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoMaterialSubtarea whereSubtareaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoMaterialSubtarea whereTareaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoMaterialSubtarea whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SeguimientoMaterialSubtarea extends Model implements Auditable
{
    use HasFactory, AuditableModel;

    protected $table = 'seguimientos_materiales_subtareas';
    protected $fillable = [
        'stock_actual',
        'cantidad_utilizada',
        'subtarea_id',
        'empleado_id',
        'grupo_id',
        'detalle_producto_id',
        'cliente_id',
    ];

    /*************
     * Relaciones
     *************/
    public function subtarea()
    {
        return $this->hasOne(Subtarea::class, 'id', 'subtarea_id');
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

    /*********
     * Scopes
     *********/

    public function scopeResponsable($query)
    {
        return $query->where('empleado_id', Auth::user()->empleado->id);
    }

    public function materialEmpleadoTarea()
    {
        return $this->belongsTo(MaterialEmpleadoTarea::class, 'detalle_producto_id', 'detalle_producto_id');
    }
}
