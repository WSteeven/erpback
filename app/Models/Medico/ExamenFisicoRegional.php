<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class ExamenFisicoRegional extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_examenes_fisicos_regionales';
    protected $fillable = [
        'categoria_examen_fisico_id',
        'observacion',
        'examen_fisico_regionalable_id',
        'examen_fisico_regionalable_type',
    ];

    public function categoriaexamenFisico(){
        return $this->hasOne(CategoriaExamenFisico::class,'id','categoria_examen_fisico_id');
    }

    public function fichaPreocupacional(){
        return $this->hasOne(FichaPreocupacional::class, 'id','ficha_preocupacional_id');
    }

}
