<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
class TipoFactorRiesgo extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;
    public const FISICO=1;
    public const MECANICO=2;
    public const QUIMICO=3;
    public const BIOLOGICO=4;
    public const ERGONOMICO=5;
    public const PSICOSOCIAL=6;
    protected $table = 'med_tipos_factores_riesgos';
    protected $fillable = [
        'nombre',
    ];
}
