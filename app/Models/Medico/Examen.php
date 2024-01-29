<?php

namespace App\Models\Medico;

use App\ModelFilters\Medico\ExamenFilter;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class Examen extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel, ExamenFilter;

    protected $table = 'med_examenes';
    protected $fillable = [
        'nombre',
    ];
    private static $whiteListFilter = ['*'];

    /*************
     * Relaciones
     *************/
    public function categoria()
    {
        return $this->belongsToMany(CategoriaExamen::class, 'med_detalles_examenes', 'examen_id', 'categoria_examen_id')->withTimestamps(); ///withPivot('cantidad')->withTimestamps();
    }

    public function tipoExamen()
    {
        return $this->belongsToMany(TipoExamen::class, 'med_detalles_examenes', 'examen_id', 'tipo_examen_id')->withTimestamps(); ///withPivot('cantidad')->withTimestamps();
    }

    public function estadoSolicitudExamen()
    {
        return $this->hasMany(EstadoSolicitudExamen::class);
    }
}
