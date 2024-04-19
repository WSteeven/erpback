<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class CategoriaExamenFisico extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    const CICATRICES = 1;
    const TATUAJES = 2;
    const PIEL_FANERAS = 3;
    const PARPADOS = 4;
    const CONJUNTIVAS = 5;
    const PUPILAS = 6;
    const CORNEA = 7;
    const MOTILIDAD = 8;
    const AUDITIVOEXTERNO = 9;
    const PABELLON = 10;
    const TIMPANOS = 11;
    const LABIOS = 12;
    const LENGUA = 13;
    const FARINGUE = 14;
    const AMIGDALAS = 15;
    const DENTADURA = 16;
    const TABIQUE = 17;
    const CORNETES = 18;
    const MUCOSAS = 19;
    const SENOSPARANASALES = 20;
    const TIROIDESMASAS = 21;
    const MOVILIDAD = 22;
    const MAMAS = 23;
    const CORAZON = 24;
    const PULMONES = 25;
    const PARRILLA_COSTAL = 26;
    const VISCERAS = 27;
    const PAREDABDOMINAL = 28;
    const FLEXIBILIDAD = 29;
    const DESVIACION = 30;
    const DOLOR = 31;
    const PELVIS = 32;
    const GENITALES = 33;
    const VASCULAR = 34;
    const MIEMBROSSUPERIORES = 35;
    const MIEMBROSINFERIORES = 36;
    const FUERZA = 37;
    const SENSIBILIDAD = 38;
    const MARCHA = 39;
    const REFLEJOS = 40;

    protected $table = 'med_categorias_examenes_fisicos';
    protected $fillable = [
        'nombre',
        'region'
    ];
    private static $whiteListFilter = ['*'];

    public function regionCuerpo()
    {
        return $this->belongsTo(RegionCuerpo::class);
    }
}
