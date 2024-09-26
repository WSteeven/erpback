<?php

namespace App\Models\ComprasProveedores;

use App\Models\Pais;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class ProveedorInternacional extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable, UppercaseValuesTrait;

    protected $table = 'cmp_proveedores_internacionales';

    protected $fillable = [
        'nombre',
        'tipo', //persona juridica o natural
        'ruc',
        'pais_id',
        'direccion',
        'telefono',
        'correo',
        'sitio_web',
        'banco1',
        'numero_cuenta1',
        'codigo_swift1',
        'moneda1',
        'banco2',
        'numero_cuenta2',
        'codigo_swift2',
        'moneda2',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    private static array $whiteListFilter = ['*'];

    public function pais()
    {
        return $this->belongsTo(Pais::class);
    }
}
