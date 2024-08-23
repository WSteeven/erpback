<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * @method static filter()
 * @method static create(mixed $datos)
 */
class BancoPostulante extends Model implements Auditable
{
    use HasFactory, AuditableModel, UppercaseValuesTrait, Filterable;

    protected $table = 'rrhh_contratacion_bancos_postulantes';
    protected $fillable = [
        'user_id',
        'user_type',
        'cargo_id',
        'postulacion_id',
        'puntuacion',
        'observacion',
        'descartado', // cuando se consulta a la persona y no tiene disponibilidad para ese trabajo en ese momento y ya no desea ser contactado para futuras ofertas
        'fue_contactado', // si resulta contactado 3  veces y no hay disponibilidad se descarta automaticamente
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'descartado' => 'boolean',
    ];

    private static array $whiteListFilter = ['*'];

    public function bancable()
    {
        return $this->morphTo();
    }
}
