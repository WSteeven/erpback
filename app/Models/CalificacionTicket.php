<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class CalificacionTicket extends Model implements Auditable
{
    use HasFactory, AuditableModel;

    const SOLICITANTE = 'SOLICITANTE';
    const RESPONSABLE = 'RESPONSABLE';

    protected $table = "calificaciones_tickets";

    protected $fillable = [
        'solicitante_o_responsable',
        'observacion',
        'calificacion',
        'calificador_id',
        'ticket_id',
    ];

    public function calificador()
    {
        return $this->belongsTo(Empleado::class, 'calificador_id', 'id');
    }
}
