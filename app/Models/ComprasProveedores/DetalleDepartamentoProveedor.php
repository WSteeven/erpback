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
