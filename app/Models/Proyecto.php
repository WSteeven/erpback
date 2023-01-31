<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UppercaseValuesTrait;

class Proyecto extends Model
{
    use HasFactory, Filterable, UppercaseValuesTrait;
    protected $table = "proyectos";

    protected $fillable = [
        'codigo_proyecto',
        'nombre',
        'cliente_id',
        'canton_id',
        'coordinador_id',
        'fiscalizador_id',
        'fecha_inicio',
        'fecha_fin',
        'finalizado',
    ];

    private static $whiteListFilter = ['*'];

    // Relacion uno a muchos (inversa)
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relacion uno a muchos (inversa)
    public function coordinador()
    {
        return $this->belongsTo(Empleado::class, 'coordinador_id', 'id');
    }

    // Relacion uno a muchos (inversa)
    public function canton()
    {
        return $this->belongsTo(Canton::class);
    }
}
