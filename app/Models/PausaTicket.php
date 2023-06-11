<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class PausaTicket extends Model implements Auditable
{
    use HasFactory, AuditableModel;
    public $timestamps = false;
    protected $table = "pausas_tickets";

    protected $fillable = [
        'fecha_hora_pausa',
        'fecha_hora_retorno',
        'motivo_pausa_ticket_id',
        'ticket_id',
    ];

    public function motivoPausa()
    {
        return $this->belongsTo(MotivoPausaTicket::class);
    }
}
