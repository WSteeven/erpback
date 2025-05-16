<?php

namespace App\Models\ComprasProveedores;

use App\Models\Canton;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class Beneficiario extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, Searchable, AuditableModel;

    protected $table = 'cmp_beneficiarios';
    protected $fillable = [
        'codigo_beneficiario',
        'tipo_documento',
        'identificacion_beneficiario',
        'nombre_beneficiario',
        'direccion',
        'telefono',
        'localidad',
        'correo',
        'canton_id',
    ];

    private static array $whiteListFilter = ['*'];

    public function toSearchableArray()
    {
        return [
            'identificacion_beneficiario' => $this['identificacion_beneficiario'],
            'nombre_beneficiario' => $this['nombre_beneficiario'],
        ];
    }

    /**************
     * Relaciones
     **************/
    public function canton()
    {
        return $this->belongsTo(Canton::class);
    }

    public function cuentasBancarias()
    {
        return $this->hasMany(CuentaBancaria::class, 'beneficiario_id');
    }
}
