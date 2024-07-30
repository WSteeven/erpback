<?php

namespace App\Models\Intranet;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Evento extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'intra_eventos';
    protected $fillable = [
        'titulo',
        'tipo_evento_id',
        'anfitrion_id',
        'descripcion',
        'fecha_hora_inicio',
        'fecha_hora_fin',
        'es_editable',
        'es_personalizado',
    ];

    private static array $whiteListFilter = [
        '*',
    ];

    public function tipoEvento(){
        return $this->belongsTo(TipoEvento::class);
    }

    public function anfitrion(){
        return $this->belongsTo(Empleado::class);
    }
}
