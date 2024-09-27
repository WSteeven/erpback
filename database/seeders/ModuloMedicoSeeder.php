<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\RecursosHumanos\Medico\CategoriaExamenFisicoSeeder;
use Database\Seeders\RecursosHumanos\Medico\CategoriaExamenSeeder;
use Database\Seeders\RecursosHumanos\Medico\CategoriaFactorRiesgoSeeder;
use Database\Seeders\RecursosHumanos\Medico\ConfiguracionExamenCampoSeeder;
use Database\Seeders\RecursosHumanos\Medico\ConfiguracionExamenCategoriaSeeder;
use Database\Seeders\RecursosHumanos\Medico\CuestionarioDiagnosticoConsumoDrogasSeeder;
use Database\Seeders\RecursosHumanos\Medico\CuestionarioSeeder;
use Database\Seeders\RecursosHumanos\Medico\DetalleExamenSeeder;
use Database\Seeders\RecursosHumanos\Medico\EstadoExamenSeeder;
use Database\Seeders\RecursosHumanos\Medico\ExamenesOrganosReproductivosSeeder;
use Database\Seeders\RecursosHumanos\Medico\ExamenSeeder;
use Database\Seeders\RecursosHumanos\Medico\IdentidadGeneroSeeder;
use Database\Seeders\RecursosHumanos\Medico\LaboratorioClinicoSeeder;
use Database\Seeders\RecursosHumanos\Medico\OrganosSistemasSeeder;
use Database\Seeders\RecursosHumanos\Medico\OrientacionSexualSeeder;
use Database\Seeders\RecursosHumanos\Medico\PermisosMedicoSeeder;
use Database\Seeders\RecursosHumanos\Medico\PreguntasDiagnosticoConsumoDrogasSeeder;
use Database\Seeders\RecursosHumanos\Medico\PreguntaSeeder;
use Database\Seeders\RecursosHumanos\Medico\RegionCuerpoSeeder;
use Database\Seeders\RecursosHumanos\Medico\ReligionSeeder;
use Database\Seeders\RecursosHumanos\Medico\RespuestasDiagnosticoConsumoDrogasSeeder;
use Database\Seeders\RecursosHumanos\Medico\RespuestaSeeder;
use Database\Seeders\RecursosHumanos\Medico\TipoAntecedenteFamiliarSeeder;
use Database\Seeders\RecursosHumanos\Medico\TipoAntecedenteSeeder;
use Database\Seeders\RecursosHumanos\Medico\TipoAptitudMedicaLaboralSeeder;
use Database\Seeders\RecursosHumanos\Medico\TipoAptitudSeeder;
use Database\Seeders\RecursosHumanos\Medico\TipoCuestionarioSeeder;
use Database\Seeders\RecursosHumanos\Medico\TipoEvaluacionMedicaRetiroSeeder;
use Database\Seeders\RecursosHumanos\Medico\TipoExamenSeeder;
use Database\Seeders\RecursosHumanos\Medico\TipoFactorRiesgoSeeder;
use Database\Seeders\RecursosHumanos\Medico\TipoHabitoToxicoSeeder;
use Database\Seeders\RecursosHumanos\Medico\TipoVacunaSeeder;
use Database\Seeders\RecursosHumanos\RecursosHumanos\TipoDiscapacidadSeeder;
use Illuminate\Database\Seeder;

class ModuloMedicoSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * php artisan db:seed --class="Database\Seeders\ModuloMedicoSeeder"
     * @return void
     */
    public function run()
    {
        /*****************
         * Modulo medico
         *****************/
        $this->call([ // No cambiar el orden
            PermisosMedicoSeeder::class,
            ExamenesOrganosReproductivosSeeder::class,
            TipoExamenSeeder::class,
            TipoCuestionarioSeeder::class,
            LaboratorioClinicoSeeder::class,
            ExamenSeeder::class,
            ConfiguracionExamenCategoriaSeeder::class,
            ConfiguracionExamenCampoSeeder::class,
            CategoriaExamenSeeder::class,
            EstadoExamenSeeder::class,
            TipoVacunaSeeder::class,
            TipoAptitudMedicaLaboralSeeder::class,
            TipoEvaluacionMedicaRetiroSeeder::class,
            DetalleExamenSeeder::class,
            /**** CUESTIONARIO PSICOSOCIAL */
            PreguntaSeeder::class,
            RespuestaSeeder::class,
            CuestionarioSeeder::class,
            /* FIN CUESTIONARIO PSICOSOCIAL */
            /**** CUESTIONARIO DE ALCOHOL Y DROGAS */
            PreguntasDiagnosticoConsumoDrogasSeeder::class,
            RespuestasDiagnosticoConsumoDrogasSeeder::class,
            CuestionarioDiagnosticoConsumoDrogasSeeder::class,
            /* FIN CUESTIONARIO DE ALCOHOL Y DROGAS */
            RegionCuerpoSeeder::class,
            CategoriaExamenFisicoSeeder::class,
            TipoFactorRiesgoSeeder::class,
            CategoriaFactorRiesgoSeeder::class,
            IdentidadGeneroSeeder::class,
            OrganosSistemasSeeder::class,
            OrientacionSexualSeeder::class,
            ReligionSeeder::class,
            TipoAntecedenteFamiliarSeeder::class,
            TipoAntecedenteSeeder::class,
            TipoAptitudSeeder::class,
            TipoHabitoToxicoSeeder::class,
            TipoDiscapacidadSeeder::class,
        ]);
    }
}
