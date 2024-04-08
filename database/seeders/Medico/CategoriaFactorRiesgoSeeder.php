<?php

namespace Database\Seeders\Medico;

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
              'tipo' => CategoriaFactorRiesgo::FISICO
            ],
            [
              'nombre' => 'Temperaturas bajas',
              'tipo' => CategoriaFactorRiesgo::FISICO
            ],
            [
              'nombre' => 'Radiación ionizante',
              'tipo' => CategoriaFactorRiesgo::FISICO
            ],
            [
              'nombre' => 'Ruido',
              'tipo' => CategoriaFactorRiesgo::FISICO
            ],
            [
              'nombre' => 'Vibración',
              'tipo' => CategoriaFactorRiesgo::FISICO
            ],
            [
              'nombre' => 'Iluminación',
              'tipo' => CategoriaFactorRiesgo::FISICO
            ],
            [
              'nombre' => 'Ventilación',
              'tipo' => CategoriaFactorRiesgo::FISICO
            ],
            [
              'nombre' => 'Atrapamiento entre máquinas',
              'tipo' => CategoriaFactorRiesgo::MECANICO
            ],
            [
              'nombre' => 'Atrapamiento entre superficies',
              'tipo' => CategoriaFactorRiesgo::MECANICO
            ],
            [
              'nombre' => 'Atrapamiento entre superficies',
              'tipo' => CategoriaFactorRiesgo::MECANICO
            ],
            [
              'nombre' => 'Atrapamiento entre objetos',
              'tipo' => CategoriaFactorRiesgo::MECANICO

            ],
            [
              'nombre' => 'Caída de objetos',
              'tipo' => CategoriaFactorRiesgo::MECANICO
            ],
            [
              'nombre' => 'Caídas al mismo nivel',
              'tipo' => CategoriaFactorRiesgo::MECANICO
            ],
            [
              'nombre' => 'Caídas a diferente nivel',
              'tipo' => CategoriaFactorRiesgo::MECANICO
            ],
            [
              'nombre' => 'Contacto eléctrico',
              'tipo' => CategoriaFactorRiesgo::MECANICO
            ],
            [
              'nombre' => 'Contacto con superficies de trabajos',
              'tipo' => CategoriaFactorRiesgo::MECANICO
            ],
            [
              'nombre' => 'Proyección de partículas - fragmentos',
              'tipo' => CategoriaFactorRiesgo::MECANICO
            ],
            [
              'nombre' => 'Proyección de fluidos',
              'tipo' => CategoriaFactorRiesgo::MECANICO
            ],
            [
              'nombre' => 'Pinchazos',
              'tipo' => CategoriaFactorRiesgo::MECANICO
            ],
            [
              'nombre' => 'Cortes',
              'tipo' => CategoriaFactorRiesgo::MECANICO
            ],
            [
              'nombre' => 'Sólidos',
              'tipo' => CategoriaFactorRiesgo::QUIMICO
            ],
            [
              'nombre' => 'Polvos',
              'tipo' => CategoriaFactorRiesgo::QUIMICO
            ],
            [
              'nombre' => 'Humos',
              'tipo' => CategoriaFactorRiesgo::QUIMICO
            ],
            [
              'nombre' => 'Líquidos',
              'tipo' => CategoriaFactorRiesgo::QUIMICO
            ],
            [
              'nombre' => 'Vapores',
              'tipo' => CategoriaFactorRiesgo::QUIMICO
            ],
            [
              'nombre' => 'Aerosoles',
              'tipo' => CategoriaFactorRiesgo::QUIMICO
            ],
            [
              'nombre' => 'Neblinas',
              'tipo' => CategoriaFactorRiesgo::QUIMICO
            ],
            [
              'nombre' => 'Gaseosos',
              'tipo' => CategoriaFactorRiesgo::QUIMICO
            ],
            [
              'nombre' => 'Virus',
              'tipo' => CategoriaFactorRiesgo::BIOLOGICO
            ],
            [
              'nombre' => 'Hongos',
              'tipo' => CategoriaFactorRiesgo::BIOLOGICO
            ],
            [
              'nombre' => 'Bacterias',
              'tipo' => CategoriaFactorRiesgo::BIOLOGICO
            ],
            [
              'nombre' => 'Exposición a vectores',
              'tipo' => CategoriaFactorRiesgo::BIOLOGICO
            ],
            [
              'nombre' => 'Exposición a animales selváticos',
              'tipo' => CategoriaFactorRiesgo::BIOLOGICO
            ],
            [
              'nombre' => 'Manejo manual de cargas',
              'tipo' => CategoriaFactorRiesgo::ERGONOMICO
            ],
            [
              'nombre' => 'Movimiento repetitivo',
              'tipo' => CategoriaFactorRiesgo::ERGONOMICO
            ],
            [
              'nombre' => 'Posturas forzadas',
              'tipo' => CategoriaFactorRiesgo::ERGONOMICO
            ],
            [
              'nombre' => 'Trabajos con Pvd',
              'tipo' => CategoriaFactorRiesgo::ERGONOMICO
            ],
            [
              'nombre' => 'Exposición a vectores',
              'tipo' => CategoriaFactorRiesgo::ERGONOMICO
            ],
            [
              'nombre' => 'Exposición a animales selváticos',
              'tipo' => CategoriaFactorRiesgo::ERGONOMICO
            ],
            [
              'nombre' => 'Monotonía del trabajo',
              'tipo' => CategoriaFactorRiesgo::PSICOSOCIAL
            ],[
              'nombre' => 'Sobrecarga laboral',
              'tipo' => CategoriaFactorRiesgo::PSICOSOCIAL
            ],[
              'nombre' => 'Minuciosidad de la tarea',
              'tipo' => CategoriaFactorRiesgo::PSICOSOCIAL
            ],[
              'nombre' => 'Alta responsabilidad',
              'tipo' => CategoriaFactorRiesgo::PSICOSOCIAL
            ],[
              'nombre' => 'Autonomía en la toma de decisiones',
              'tipo' => CategoriaFactorRiesgo::PSICOSOCIAL
            ],[
              'nombre' => 'Supervisión y estilos de dirección deficiente',
              'tipo' => CategoriaFactorRiesgo::PSICOSOCIAL
            ],[
              'nombre' => 'Conflicto de rol',
              'tipo' => CategoriaFactorRiesgo::PSICOSOCIAL
            ],[
              'nombre' => 'Falta de claridad en las funciones',
              'tipo' => CategoriaFactorRiesgo::PSICOSOCIAL
            ],[
              'nombre' => 'Incorrecta distribución del trabajo',
              'tipo' => CategoriaFactorRiesgo::PSICOSOCIAL
            ],[
              'nombre' => 'Turnos rotativos',
              'tipo' => CategoriaFactorRiesgo::PSICOSOCIAL
            ],[
              'nombre' => 'Relaciones interpersonales',
              'tipo' => CategoriaFactorRiesgo::PSICOSOCIAL
            ],[
              'nombre' => 'Inestabilidad laboral',
              'tipo' => CategoriaFactorRiesgo::PSICOSOCIAL
            ],
        ]);
    }
}
