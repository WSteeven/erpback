<?php

namespace App\Models\Tickets;

use App\Models\Empleado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class ComentarioTicket extends Model implements Auditable
{
    use HasFactory, Filterable, AuditableModel;

    protected $table = 'tckt_comentarios_tickets';
    protected $fillable = [
        'comentario',
        'empleado_id',
        'ticket_id',
    ];
    private static $whiteListFilter = ['*'];

    /**************
     * Relaciones
     **************/
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
