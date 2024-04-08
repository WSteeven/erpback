<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class CategoriaFactorRiesgo extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;
public const FISICO='Físico';
public const MECANICO='Mecánico';
public const QUIMICO='Químico';
public const BIOLOGICO='Biológico';
public const ERGONOMICO='Ergonómico';
public const PSICOSOCIAL='Psicosocial';
    protected $table = 'med_categorias_factores_riesgos';
    protected $fillable = [
        'nombre',
        'tipo',
    ];
}
