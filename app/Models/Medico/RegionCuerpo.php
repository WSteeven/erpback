<?php

namespace App\Models\Medico;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;

class RegionCuerpo extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;
    protected $table = 'med_regiones_cuerpo';
    protected $fillable = [
        'nombre',
    ];
    public const PIEL = 1;
    public const OJOS = 2;
    public const OIDO = 3;
    public const OROFARINGUE = 4;
    public const NARIZ = 5;
    public const CUELLO = 6;
    public const TORAX = 7;
    public const ABDOMEN = 8;
    public const COLUMNA = 9;
    public const PELVIS = 10;
    public const EXTREMIDADES = 11;
    public const NEUROLOGICO = 12;

    private static $whiteListFilter = ['*'];

    public function categoriaExamen()
    {
        return $this->hasMany(CategoriaExamenFisico::class);
    }
}
