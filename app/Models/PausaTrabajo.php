<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class PausaTrabajo extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    public $timestamps = false;
    protected $table = "pausas_trabajos";

    protected $fillable = [
        'fecha_hora_pausa',
        'fecha_hora_retorno',
        'motivo',
        'trabajo_id'
    ];
}
