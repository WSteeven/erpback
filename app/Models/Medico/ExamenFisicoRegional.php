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
        'preocupacional_id',
    ];

    public function categoriaexamenFisico(){
        return $this->hasOne(CategoriaExamenFisico::class,'id','categoria_examen_fisico_id');
    }

    public function preocupacional(){
        return $this->hasOne(Preocupacional::class, 'id','preocupacional_id');
    }

}
