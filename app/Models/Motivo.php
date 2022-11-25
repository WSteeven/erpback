<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class Motivo extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait;
    use AuditableModel;
    use Filterable;

    protected $table = 'motivos';
    protected $fillable = ['nombre', 'tipo_transaccion_id'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static $whiteListFilter = ['*'];
    
    /**
     * Relacion uno a muchos (inversa)
     * Uno o varios subtipos pertenecen a un tipo de transaccion
     */
    public function tipoTransaccion()
    {
        return $this->belongsTo(TipoTransaccion::class);
    }

    public function transaccion()
    {
        return $this->hasOne(TransaccionBodega::class);
    }
}
