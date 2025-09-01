<?php

namespace App\Models\FondosRotativos;

use App\Models\Departamento;
use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class Valija extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;

    protected $table = 'fr_valijas';
    protected $fillable = [
        'envio_valija_id',
        'departamento_id',
        'descripcion', 'destinatario_id', 'imagen_evidencia',];

    private static array $whiteListFilter = ['*'];


    public function envioValija()
    {
        return $this->belongsTo(EnvioValija::class);
    }

    /*    public function empleado(){
            return $this->belongsTo(Empleado::class);
        }*/
    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function destinatario()
    {
        return $this->belongsTo(Empleado::class, 'destinatario_id');
    }

    /*    public function gasto()
        {
            return $this->belongsTo(Gasto::class);
        }*/
}
