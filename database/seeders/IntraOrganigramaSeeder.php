<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IntraOrganigramaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Obtener todos los IDs de empleados existentes
        $empleadoIds = DB::table('empleados')->pluck('id')->toArray();

        // Lista de departamentos y subdepartamentos
        $departamentos = [
            'GERENCIA',
            'RRHH',
            'BODEGA',
            'INFORMATICA',
            'CONTABILIDAD',
            'SSA',
            'TECNICO',
            'COORDINACION NEDETEL',
            'COOR. TELCONET'
        ];

        // Lista de cargos realistas
        $cargos = [
            'Gerente General',
            'Director de Recursos Humanos',
            'Jefe de Bodega',
            'Analista de Sistemas',
            'Contador',
            'Especialista en Seguridad y Salud',
            'Técnico de Mantenimiento',
            'Coordinador de Proyectos',
            'Jefe de Operaciones',
            'Asistente Administrativo',
            'Desarrollador de Software',
            'Analista Financiero',
            'Gerente de Marketing',
            'Líder de Ventas',
            'Auxiliar Contable',
            'Coordinador de Logística',
            'Supervisor de Producción',
            'Especialista en Soporte Técnico',
            'Diseñador Gráfico',
            'Ingeniero de Redes',
            'Responsable de Calidad',
            'Encargado de Compras'
        ];

        // Generar 50 registros para intra_organigrama
        for ($i = 0; $i < 50; $i++) {
            // Seleccionar un empleado aleatorio
            $empleado_id = $empleadoIds[array_rand($empleadoIds)];

            // Inicializar jefe_id
            $jefe_id = null;

            // Determinar si el empleado será su propio jefe o tendrá uno diferente
            if (rand(0, 1) === 1) {
                // Posibilidad de ser su propio jefe
                $jefe_id = $empleado_id;
            } else {
                // Seleccionar un jefe diferente al empleado actual
                do {
                    $jefe_id = $empleadoIds[array_rand($empleadoIds)];
                } while ($jefe_id === $empleado_id); // Asegurar que el jefe no sea el mismo empleado
            }

            // Seleccionar un departamento aleatorio
            $departamento = $departamentos[array_rand($departamentos)];

            // Seleccionar un cargo aleatorio
            $cargo = $cargos[array_rand($cargos)];

            // Insertar registro en intra_organigrama
            DB::table('intra_organigrama')->insert([
                'empleado_id' => $empleado_id,
                'cargo' => $cargo,
                'jefe_id' => $jefe_id,
                'departamento' => $departamento,
                'nivel' => rand(1, 5), // Niveles de 1 a 5
                'tipo' => (rand(0, 1) === 1) ? 'interno' : 'externo',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
