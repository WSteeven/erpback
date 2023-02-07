<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class UnidadMedida extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;

    protected $table = 'unidades_medidas';
    protected $fillable =[
        'nombre',
        'simbolo',
    ];

    /**
     * Relacion uno a muchos.
     * Una unidad de medida está en varios productos
     */
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

}
