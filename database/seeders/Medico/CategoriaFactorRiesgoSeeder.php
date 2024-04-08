<?php

namespace Database\Seeders\Medico;

use App\Models\Medico\TipoFactorRiesgo;
use App\Models\Medico\CategoriaFactorRiesgo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriaFactorRiesgoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CategoriaFactorRiesgo::insert([
            [
              'nombre' => 'Temperaturas altas',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::FISICO
            ],
            [
              'nombre' => 'Temperaturas bajas',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::FISICO
            ],
            [
              'nombre' => 'Radiación ionizante',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::FISICO
            ],
            [
              'nombre' => 'Ruido',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::FISICO
            ],
            [
              'nombre' => 'Vibración',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::FISICO
            ],
            [
              'nombre' => 'Iluminación',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::FISICO
            ],
            [
              'nombre' => 'Ventilación',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::FISICO
            ],
            [
              'nombre' => 'Atrapamiento entre máquinas',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::MECANICO
            ],
            [
              'nombre' => 'Atrapamiento entre superficies',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::MECANICO
            ],
            [
              'nombre' => 'Atrapamiento entre superficies',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::MECANICO
            ],
            [
              'nombre' => 'Atrapamiento entre objetos',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::MECANICO

            ],
            [
              'nombre' => 'Caída de objetos',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::MECANICO
            ],
            [
              'nombre' => 'Caídas al mismo nivel',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::MECANICO
            ],
            [
              'nombre' => 'Caídas a diferente nivel',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::MECANICO
            ],
            [
              'nombre' => 'Contacto eléctrico',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::MECANICO
            ],
            [
              'nombre' => 'Contacto con superficies de trabajos',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::MECANICO
            ],
            [
              'nombre' => 'Proyección de partículas - fragmentos',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::MECANICO
            ],
            [
              'nombre' => 'Proyección de fluidos',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::MECANICO
            ],
            [
              'nombre' => 'Pinchazos',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::MECANICO
            ],
            [
              'nombre' => 'Cortes',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::MECANICO
            ],
            [
              'nombre' => 'Sólidos',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::QUIMICO
            ],
            [
              'nombre' => 'Polvos',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::QUIMICO
            ],
            [
              'nombre' => 'Humos',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::QUIMICO
            ],
            [
              'nombre' => 'Líquidos',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::QUIMICO
            ],
            [
              'nombre' => 'Vapores',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::QUIMICO
            ],
            [
              'nombre' => 'Aerosoles',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::QUIMICO
            ],
            [
              'nombre' => 'Neblinas',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::QUIMICO
            ],
            [
              'nombre' => 'Gaseosos',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::QUIMICO
            ],
            [
              'nombre' => 'Virus',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::BIOLOGICO
            ],
            [
              'nombre' => 'Hongos',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::BIOLOGICO
            ],
            [
              'nombre' => 'Bacterias',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::BIOLOGICO
            ],
            [
              'nombre' => 'Exposición a vectores',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::BIOLOGICO
            ],
            [
              'nombre' => 'Exposición a animales selváticos',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::BIOLOGICO
            ],
            [
              'nombre' => 'Manejo manual de cargas',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::ERGONOMICO
            ],
            [
              'nombre' => 'Movimiento repetitivo',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::ERGONOMICO
            ],
            [
              'nombre' => 'Posturas forzadas',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::ERGONOMICO
            ],
            [
              'nombre' => 'Trabajos con Pvd',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::ERGONOMICO
            ],
            [
              'nombre' => 'Exposición a vectores',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::ERGONOMICO
            ],
            [
              'nombre' => 'Exposición a animales selváticos',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::ERGONOMICO
            ],
            [
              'nombre' => 'Monotonía del trabajo',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::PSICOSOCIAL
            ],[
              'nombre' => 'Sobrecarga laboral',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::PSICOSOCIAL
            ],[
              'nombre' => 'Minuciosidad de la tarea',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::PSICOSOCIAL
            ],[
              'nombre' => 'Alta responsabilidad',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::PSICOSOCIAL
            ],[
              'nombre' => 'Autonomía en la toma de decisiones',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::PSICOSOCIAL
            ],[
              'nombre' => 'Supervisión y estilos de dirección deficiente',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::PSICOSOCIAL
            ],[
              'nombre' => 'Conflicto de rol',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::PSICOSOCIAL
            ],[
              'nombre' => 'Falta de claridad en las funciones',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::PSICOSOCIAL
            ],[
              'nombre' => 'Incorrecta distribución del trabajo',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::PSICOSOCIAL
            ],[
              'nombre' => 'Turnos rotativos',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::PSICOSOCIAL
            ],[
              'nombre' => 'Relaciones interpersonales',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::PSICOSOCIAL
            ],[
              'nombre' => 'Inestabilidad laboral',
              'tipo_factor_riesgo_id' => TipoFactorRiesgo::PSICOSOCIAL
            ],
        ]);
    }
}
