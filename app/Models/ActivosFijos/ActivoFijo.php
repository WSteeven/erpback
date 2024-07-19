<?php

namespace App\Models\ActivosFijos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivoFijo extends Model implements Auditable
{
    use HasFactory, Filterable, AuditableModel;

    protected $table = 'af_activos_fijos';
    protected $fillable = [
        'nombre',
        'direccion',
        'celular',
        'correo',
        'coordenadas',
        'activo',
        'canton_id',
    ];

    private static $whiteListFilter = ['*'];

    protected $casts = [
        'activo' => 'boolean'
    ];
}
