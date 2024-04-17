<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\Medico\CategoriaExamenFisicoSeeder;
use Database\Seeders\Medico\CategoriaFactorRiesgoSeeder;
use Database\Seeders\Medico\ExamenesOrganosReproductivosSeeder;
use Database\Seeders\Medico\IdentidadGeneroSeeder;
use Database\Seeders\Medico\OrganosSistemasSeeder;
use Database\Seeders\Medico\OrientacionSexualSeeder;
use Database\Seeders\Medico\PermisosMedicoSeeder;
use Database\Seeders\Medico\RegionCuerpoSeeder;
use Database\Seeders\Medico\ReligionSeeder;
use Database\Seeders\Medico\TipoAntecedenteFamiliarSeeder;
use Database\Seeders\Medico\TipoAntecedenteSeeder;
use Database\Seeders\Medico\TipoAptitudMedicaLaboralSeeder;
use Database\Seeders\Medico\TipoAptitudSeeder;
use Database\Seeders\Medico\TipoDescripcionAntecedenteTrabajoSeeder;
use Database\Seeders\Medico\TipoEvaluacionMedicaRetiroSeeder;
use Database\Seeders\Medico\TipoFactorRiesgoSeeder;
use Database\Seeders\Medico\TipoHabitoToxicoSeeder;
use Database\Seeders\RecursosHumanos\TipoDiscapacidadSeeder;
use Illuminate\Database\Seeder;

class ModuloMedicoSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /*****************
         * Modulo medico
         *****************/
        $this->call([
            ExamenesOrganosReproductivosSeeder::class,
            TipoExamenSeeder::class,
            TipoCuestionarioSeeder::class,
            RespuestaSeeder::class,
            RespuestasDiagnosticoConsumoDrogasSeeder::class,
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
            PreguntaSeeder::class,
            CuestionarioSeeder::class,
            // CuestionarioDiagnosticoConsumoDrogasSeeder::class,
            RegionCuerpoSeeder::class,
            CategoriaExamenFisicoSeeder::class,
            TipoFactorRiesgoSeeder::class,
            CategoriaFactorRiesgoSeeder::class,
            CategoriaExamenFisicoSeeder::class,
            IdentidadGeneroSeeder::class,
            OrganosSistemasSeeder::class,
            OrientacionSexualSeeder::class,
            ReligionSeeder::class,
            TipoAntecedenteFamiliarSeeder::class,
            TipoAntecedenteSeeder::class,
            TipoAptitudMedicaLaboralSeeder::class,
            TipoAptitudSeeder::class,
            // TipoDescripcionAntecedenteTrabajoSeeder::class,
            TipoEvaluacionMedicaRetiroSeeder::class,
            TipoHabitoToxicoSeeder::class,
            PermisosMedicoSeeder::class,
            TipoDiscapacidadSeeder::class,
        ]);
    }
}
