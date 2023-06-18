<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class TicketRechazado extends Model implements Auditable
{
    use HasFactory, AuditableModel;
    public $timestamps = false;
    protected $table = "tickets_rechazados";

    protected $fillable = [
        'fecha_hora',
        'motivo',
        'responsable_id',
        'ticket_id',
    ];

    public function responsable()
    {
        return $this->belongsTo(Empleado::class, 'responsable_id', 'id');
    }
}
