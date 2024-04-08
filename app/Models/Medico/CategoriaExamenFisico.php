<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class CategoriaExamenFisico extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;
    public const PIEL ='piel';
    public const OJOS ='ojos';
    public const OIDO ='oido';
    public const OROFARINGUE ='orofaringue';
    public const NARIZ ='nariz';
    public const CUELLO ='cuello';
    public const TORAX ='tórax';
    public const ABDOMEN ='abdomen';
    public const COLUMNA ='columna';
    public const PELVIS ='pelvis';
    public const EXTREMIDADES ='extremidades';
    public const NEUROLOGICO ='neurológico';

    protected $table = 'med_categorias_examenes_fisicos';
    protected $fillable = [
        'nombre',
        'region'
    ];
}
