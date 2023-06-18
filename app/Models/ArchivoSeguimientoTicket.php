<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class ArchivoSeguimientoTicket extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable;
    protected $table = 'archivos_seguimientos_tickets';
    protected $fillable = ['nombre', 'ruta', 'tamanio_bytes', 'ticket_id'];

    private static $whiteListFilter = ['*'];
}
