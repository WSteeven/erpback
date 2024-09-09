<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class MedicacionConsulta extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_medicaciones_consultas';
    protected $fillable = [
        'consulta_id',
        'receta_id',
    ];
    public function consulta()
    {
        return $this->belongsTo(Consulta::class, 'consulta_id');
    }
    public function receta()
    {
        return $this->belongsTo(Receta::class, 'receta_id');
    }
}
