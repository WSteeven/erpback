<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class EsquemaVacuna extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_esquemas_vacunas';
    protected $fillable = [
        'nombre_vacuna',
        'dosis_totales',
        'dosis_aplicadas',
        'observacion',
        'url_certificado',
        'registro_examen_id',
    ];

    // Relaciones
    public function registroExamen()
    {
        return $this->belongsTo(RegistroExamen::class);
    }
}
