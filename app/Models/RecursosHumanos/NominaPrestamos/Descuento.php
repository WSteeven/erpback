<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;


class Descuento extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait, Filterable;
    use AuditableModel;

    protected $table = 'rrhh_nomina_descuentos';
    protected $fillable = [
        'fecha_descuento',
        'empleado_id',
        'tipo_descuento_id',
        'multa_id',
        'descripcion',
        'valor',
        'cantidad_cuotas',
        'mes_inicia_cobro',
        'pagado',
    ];
    protected $casts = [
        'pagado' => 'boolean',
    ];

    private static array $whiteListFilter = ['*'];


    public function cuotas()
    {
        return $this->hasMany(CuotaDescuento::class);
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function tipoDescuento(){
        return $this->belongsTo(DescuentosGenerales::class);
    }

    public function multa(){
        return $this->belongsTo(Multas::class);
    }

}
