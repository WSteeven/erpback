<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermisosMedicoSeeder extends Seeder
{
    const CREAR = 'puede.crear';
    const ACCEDER = 'puede.acceder'; // Formulario
    const VER = 'puede.ver'; // Consultar index y show
    const EDITAR = 'puede.editar';
    const RECHAZAR = 'puede.rechazar';
    const AUTORIZAR = 'puede.autorizar';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /***************
         Modulo médico
         ***************/
        // php artisan db:seed --class=PermisosMedicoSeeder

        $medico = Role::firstOrCreate(['name' => User::ROL_MEDICO]);
        $empleado = Role::firstOrCreate(['name' => User::ROL_EMPLEADO]);
        $compras = Role::firstOrCreate(['name' => User::ROL_COMPRAS]);

        // Modulo medico
        Permission::firstOrCreate(['name' => self::VER . '.modulo_medico'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.modulo_medico'])->syncRoles([$empleado]);

        // Tipos de examenes
        Permission::firstOrCreate(['name' => self::VER . '.tipos_examenes'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.tipos_examenes'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::EDITAR . '.tipos_examenes'])->syncRoles([$empleado]);

        // Examenes
        Permission::firstOrCreate(['name' => self::VER . '.examenes'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.examenes'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::EDITAR . '.examenes'])->syncRoles([$empleado]);

        // Categorias de examenes
        Permission::firstOrCreate(['name' => self::VER . '.categorias_examenes'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.categorias_examenes'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::EDITAR . '.categorias_examenes'])->syncRoles([$empleado]);

        // Tipos de vacunas
        Permission::firstOrCreate(['name' => self::VER . '.tipos_vacunas'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.tipos_vacunas'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::EDITAR . '.tipos_vacunas'])->syncRoles([$empleado]);

        // Esquemas de vacunas
        Permission::firstOrCreate(['name' => self::VER . '.esquemas_vacunas'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.esquemas_vacunas'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::EDITAR . '.esquemas_vacunas'])->syncRoles([$empleado]);

        // Gestionar pacientes
        Permission::firstOrCreate(['name' => self::VER . '.gestionar_pacientes'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.gestionar_pacientes'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::EDITAR . '.gestionar_pacientes'])->syncRoles([$medico]);

        // Reporte de cuestionarios psicosocial
        Permission::firstOrCreate(['name' => self::VER . '.reporte_cuestionarios_psicosocial'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.reporte_cuestionarios_psicosocial'])->syncRoles([$empleado]);

        // Citas medicas
        Permission::firstOrCreate(['name' => self::RECHAZAR. '.citas_medicas'])->syncRoles([$medico]);

        // Cies
        Permission::firstOrCreate(['name' => self::VER . '.cies'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.cies'])->syncRoles([$medico]);

        // Laboratorios clinicos
        Permission::firstOrCreate(['name' => self::VER . '.laboratorios_clinicos'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.laboratorios_clinicos'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::CREAR. '.laboratorios_clinicos'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::EDITAR . '.laboratorios_clinicos'])->syncRoles([$medico]);

        // Registros empleados examenes
        Permission::firstOrCreate(['name' => self::VER . '.registros_empleados_examenes'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::CREAR. ".registros_empleados_examenes"])->syncRoles([$medico]);

        //- Solicitudes de examenes (Agrupa los examenes solicitados)
        Permission::firstOrCreate(['name' => self::VER . '.solicitudes_examenes'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.solicitudes_examenes'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::CREAR. '.solicitudes_examenes'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::EDITAR . '.solicitudes_examenes'])->syncRoles([$medico, $compras]);
        Permission::firstOrCreate(['name' => self::AUTORIZAR . '.solicitudes_examenes'])->syncRoles([$compras]); // yloja
        //-- Estados solicitudes examenes (Examenes solicitados)
        Permission::firstOrCreate(['name' => self::CREAR. ".estados_solicitudes_examenes"])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::VER. ".estados_solicitudes_examenes"])->syncRoles([$medico]);

        //- Configuraciones examenes categorias (Configuracion de formularios tabla para llenado de datos de exámenes)
        Permission::firstOrCreate(['name' => self::VER. ".configuraciones_examenes_categorias"])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::ACCEDER. ".configuraciones_examenes_categorias"])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::CREAR. ".configuraciones_examenes_categorias"])->syncRoles([$medico]);
        //-- Configuraciones examenes campos (Configuracion de formularios tabla para llenado de datos de exámenes)
        Permission::firstOrCreate(['name' => self::VER. ".configuraciones_examenes_campos"])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::ACCEDER. ".configuraciones_examenes_campos"])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::CREAR. ".configuraciones_examenes_campos"])->syncRoles([$medico]);

        //- Detalles resultados examenes (Sirve para agrupar los resultados_examenes)
        Permission::firstOrCreate(['name' => self::VER. ".detalles_resultados_examenes"])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::ACCEDER. ".detalles_resultados_examenes"])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::CREAR. ".detalles_resultados_examenes"])->syncRoles([$medico]);
        //-- Resultados de examenes (Llenado de los datos de resultados de exámenes en los formularios tablas generados por Configuracion examenes categorias)
        Permission::firstOrCreate(['name' => self::CREAR. ".resultados_examenes"])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::VER. ".resultados_examenes"])->syncRoles([$medico]);

        // Consultas
        Permission::firstOrCreate(['name' => self::VER. ".detalles_resultados_examenes"])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::ACCEDER. ".detalles_resultados_examenes"])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::CREAR. ".detalles_resultados_examenes"])->syncRoles([$medico]);
    }
}
