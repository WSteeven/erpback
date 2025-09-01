<?php

namespace App\Models\FondosRotativos;

use App\Models\Empleado;
use App\Models\FondosRotativos\Gasto\Gasto;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;


class EnvioValija extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;

    protected $table = 'fr_envios_valijas';
    protected $fillable = [
        'gasto_id',
        'empleado_id',
        'courier',
        'fotografia_guia',
        'anulado'
    ];
    protected $casts =[
        'anulado'=>'boolean'
    ];

    public function gasto()
    {
        return $this->belongsTo(Gasto::class);
    }

    public function valijas()
    {
        return $this->hasMany(Valija::class);
    }

    public function empleado(){
        return $this->belongsTo(Empleado::class);
    }


}
