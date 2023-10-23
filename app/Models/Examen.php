<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class Examen extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_examenes';
    protected $fillable = [
        'nombre',
        'ids_cargos_acceso',
        'categoria_examen_id',
    ];

    // Relaciones
    public function categoriaExamen()
    {
        return $this->belongsTo(CategoriaExamen::class);
    }
}
