<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\Medico\CategoriaExamenFisicoSeeder;
use Database\Seeders\Medico\CategoriaExamenSeeder;
use Database\Seeders\Medico\CategoriaFactorRiesgoSeeder;
use Database\Seeders\Medico\ConfiguracionExamenCampoSeeder;
use Database\Seeders\Medico\ConfiguracionExamenCategoriaSeeder;
use Database\Seeders\Medico\CuestionarioDiagnosticoConsumoDrogasSeeder;
use Database\Seeders\Medico\CuestionarioSeeder;
use Database\Seeders\Medico\DetalleExamenSeeder;
use Database\Seeders\Medico\EstadoExamenSeeder;
use Database\Seeders\Medico\ExamenesOrganosReproductivosSeeder;
use Database\Seeders\Medico\ExamenSeeder;
use Database\Seeders\Medico\IdentidadGeneroSeeder;
use Database\Seeders\Medico\LaboratorioClinicoSeeder;
use Database\Seeders\Medico\OrganosSistemasSeeder;
use Database\Seeders\Medico\OrientacionSexualSeeder;
use Database\Seeders\Medico\PermisosMedicoSeeder;
use Database\Seeders\Medico\PreguntasDiagnosticoConsumoDrogasSeeder;
use Database\Seeders\Medico\PreguntaSeeder;
use Database\Seeders\Medico\RegionCuerpoSeeder;
use Database\Seeders\Medico\ReligionSeeder;
use Database\Seeders\Medico\RespuestasDiagnosticoConsumoDrogasSeeder;
use Database\Seeders\Medico\RespuestaSeeder;
use Database\Seeders\Medico\TipoAntecedenteFamiliarSeeder;
use Database\Seeders\Medico\TipoAntecedenteSeeder;
use Database\Seeders\Medico\TipoAptitudMedicaLaboralSeeder;
use Database\Seeders\Medico\TipoAptitudSeeder;
use Database\Seeders\Medico\TipoCuestionarioSeeder;
use Database\Seeders\Medico\TipoDescripcionAntecedenteTrabajoSeeder;
use Database\Seeders\Medico\TipoEvaluacionMedicaRetiroSeeder;
use Database\Seeders\Medico\TipoExamenSeeder;
use Database\Seeders\Medico\TipoFactorRiesgoSeeder;
use Database\Seeders\Medico\TipoHabitoToxicoSeeder;
use Database\Seeders\Medico\TipoVacunaSeeder;
use Database\Seeders\RecursosHumanos\TipoDiscapacidadSeeder;
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
