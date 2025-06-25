<?php

namespace App\Models\Bodega;

use App\Models\Inventario;
use App\Models\TransaccionBodega;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class Lote extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait, Filterable;
    use AuditableModel;

    protected $table = 'bod_lotes';
    protected $fillable = [
      'inventario_id',
      'transaccion_id',
      'cant_ingresada',
      'cant_disponible',
      'fecha_vencimiento',
    ];

    private static array $whiteListFilter = ['*'];

    public function inventario(){
        return $this->belongsTo(Inventario::class, 'inventario_id');
    }
    public function transaccion(){
        return $this->belongsTo(TransaccionBodega::class, 'transaccion_id');
    }
}
