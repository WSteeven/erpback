<?php

namespace App\Models\Tareas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class SolicitudAts extends Model implements Auditable
{
    use HasFactory, AuditableModel;

    protected $table = 'tar_solicitudes_ats';
    protected $fillable = [
        'ticket_id',
        'subtarea_id',
    ];
}
