<?php

namespace Src\App\Medico;


use App\Models\Medico\AntecedenteGinecoObstetrico;
use App\Models\Medico\AntecedentePersonal;
use App\Models\Medico\ConstanteVital;
use App\Models\Medico\DescripcionAntecedenteTrabajo;
use App\Models\Medico\FichaAptitud;
use App\Models\Medico\ProfesionalSalud;
use Illuminate\Support\Facades\Log;

class FichaAptitudService
{
    private $ficha_aptitud_id;
    private $ficha_aptitud;
    private $profesional_salud;
    public function __construct($ficha_aptitud_id)
    {
        $this->ficha_aptitud_id = $ficha_aptitud_id;
        $this->ficha_aptitud = FichaAptitud::find($ficha_aptitud_id);
    }
    public function insertarProfesionalSalud(ProfesionalSalud $profesional_salud)
    {
        $profesional_salud->ficha_aptitud_id =  $this->ficha_aptitud_id;
        $this->profesional_salud = $profesional_salud->save();

    }

}
