<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class PausaSubtarea extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    public $timestamps = false;
    protected $table = "pausas_subtareas";

    protected $fillable = [
        'fecha_hora_pausa',
        'fecha_hora_retorno',
        'motivo_pausa_id',
        'subtarea_id'
    ];

    public function motivoPausa()
    {
        return $this->belongsTo(MotivoPausa::class);
    }
}
