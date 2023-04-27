<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UppercaseValuesTrait;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class ClienteFinal extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;

    protected $table = 'clientes_finales';
    protected $fillable = [
        'id_cliente_final',
        'nombres',
        'apellidos',
        'nombres',
        'apellidos',
        'celular',
        'parroquia',
        'direccion',
        'referencia',
        'cedula',
        'correo',
        'coordenadas',
        'activo',
        'provincia_id',
        'canton_id',
        'cliente_id',
    ];
    protected $casts = [
        'activo' => 'boolean',
    ];

    public function provincia()
    {
        return $this->belongsTo(Provincia::class);
    }

    public function canton()
    {
        return $this->belongsTo(Canton::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
