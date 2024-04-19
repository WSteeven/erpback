<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class ResultadoHabitoToxico extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_resultados_habitos_toxicos';
    protected $fillable = [
        'tipo_habito_toxico_id',
        'tiempo_consumo_meses',
        'cantidad',
        'ex_consumidor',
        'tiempo_abstinencia_meses',
        'habito_toxicable_id',
        'habito_toxicable_type',
    ];
    public function tipoHabitoToxico()
    {
        return $this->hasOne(TipoHabitoToxico::class, 'id', 'tipo_habito_toxico_id');
    }

    // Relación polimorfica
    public function habitable()
    {
        return $this->morphTo();
    }
}
